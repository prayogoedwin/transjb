<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DatabaseBackupService
{
    protected string $backupPath = 'backups';
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path($this->backupPath);
        $this->ensureBackupDirExists();
    }

    /**
     * Ensure backup directory exists
     */
    protected function ensureBackupDirExists(): void
    {
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }
    }

    /**
     * Create a new database backup
     *
     * @param string|null $description Optional description for the backup
     * @return array
     */
    public function backup(?string $description = null): array
    {
        try {
            $timestamp = now();
            $backupName = $timestamp->format('Y-m-d_H-i-s');
            $zipFileName = "{$backupName}.zip";
            $zipFilePath = $this->backupDir . DIRECTORY_SEPARATOR . $zipFileName;

            // Get the database file path
            // $dbPath = database_path(config('database.connections.sqlite.database.'));
            $dbPath = config('database.connections.sqlite.database');
            if (!file_exists($dbPath)) {
                throw new Exception('Database file not found at: ' . $dbPath);
            }

            // Create README content
            $readmeContent = $this->generateReadmeContent($backupName, $timestamp, $description);
            $readmePath = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}_README.txt";
            file_put_contents($readmePath, $readmeContent);

            // Create zip archive
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
                throw new Exception('Failed to create zip archive');
            }

            // Add README file to zip
            $zip->addFile($readmePath, 'README.txt');

            // Add database file to zip
            $zip->addFile($dbPath, basename($dbPath));
            $zip->close();

            // Clean up temporary README file
            unlink($readmePath);

            // Create metadata file
            $metadataFile = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.json";
            $metadata = [
                'backup_name' => $backupName,
                'zip_file' => $zipFileName,
                'database_file' => basename($dbPath),
                'timestamp' => $timestamp->toIso8601String(),
                'date_readable' => $timestamp->format('d M Y H:i:s'),
                'file_size' => filesize($zipFilePath),
                'file_size_readable' => $this->formatBytes(filesize($zipFilePath)),
                'description' => $description ?? null,
                'status' => 'success',
            ];

            file_put_contents($metadataFile, json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            Log::info('Database backup created', ['backup' => $backupName]);

            return [
                'success' => true,
                'message' => 'Backup berhasil dibuat',
                'data' => $metadata,
            ];
        } catch (Exception $e) {
            Log::error('Database backup failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Restore database from backup
     *
     * @param string $backupName
     * @return array
     */
    public function restore(string $backupName): array
    {
        try {
            // Validate backup exists
            $zipFilePath = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.zip";
            $metadataFile = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.json";

            if (!file_exists($zipFilePath)) {
                throw new Exception('Backup file not found: ' . $backupName);
            }

            if (!file_exists($metadataFile)) {
                throw new Exception('Backup metadata not found: ' . $backupName);
            }

            $metadata = json_decode(file_get_contents($metadataFile), true);
            $dbFileName = $metadata['database_file'] ?? basename(config('database.connections.sqlite.database'));
            $dbPath = database_path($dbFileName);

            // Close all connections first
            DB::disconnect();

            // Create backup of current database before restore
            if (file_exists($dbPath)) {
                copy($dbPath, $dbPath . '.backup-before-restore-' . now()->timestamp);
            }

            // Extract database from zip
            $zip = new ZipArchive();
            if ($zip->open($zipFilePath) !== true) {
                throw new Exception('Failed to open backup archive');
            }

            // Ensure backup directory exists
            $this->ensureBackupDirExists();

            // Extract to backup directory first
            $tempExtractPath = $this->backupDir . DIRECTORY_SEPARATOR . 'temp_extract';
            if (!is_dir($tempExtractPath)) {
                mkdir($tempExtractPath, 0755, true);
            }

            $zip->extractTo($tempExtractPath);
            $zip->close();

            // Copy extracted database to actual location
            $extractedDbPath = $tempExtractPath . DIRECTORY_SEPARATOR . $dbFileName;
            if (!file_exists($extractedDbPath)) {
                throw new Exception('Database file not found in backup archive');
            }

            copy($extractedDbPath, $dbPath);

            // Clean up temp extraction
            $this->deleteDirectory($tempExtractPath);

            // Reconnect to verify
            DB::reconnect();

            Log::info('Database restored from backup', ['backup' => $backupName]);

            return [
                'success' => true,
                'message' => 'Database berhasil direstore',
                'data' => $metadata,
            ];
        } catch (Exception $e) {
            Log::error('Database restore failed', ['backup' => $backupName, 'error' => $e->getMessage()]);
            DB::reconnect();

            return [
                'success' => false,
                'message' => 'Gagal merestore database: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Restore database from uploaded backup file (preserving auth tables)
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public function restoreFromFile($file): array
    {
        try {
            // Validate file is a zip
            if ($file->getMimeType() !== 'application/zip') {
                throw new Exception('File harus berupa ZIP archive');
            }

            $dbFileName = basename(config('database.connections.sqlite.database'));
            $dbPath = database_path($dbFileName);

            // Close all connections first
            DB::disconnect();

            // Create backup of current database before restore
            $backupTimestamp = now()->timestamp;
            if (file_exists($dbPath)) {
                copy($dbPath, $dbPath . '.backup-before-restore-' . $backupTimestamp);
            }

            // Extract uploaded file to temp directory
            $tempExtractPath = $this->backupDir . DIRECTORY_SEPARATOR . 'temp_extract_' . uniqid();
            if (!is_dir($tempExtractPath)) {
                mkdir($tempExtractPath, 0755, true);
            }

            $zip = new ZipArchive();
            if ($zip->open($file->getRealPath()) !== true) {
                throw new Exception('Failed to open backup archive');
            }

            $zip->extractTo($tempExtractPath);
            $zip->close();

            // Look for database file in extracted contents
            $extractedDbPath = $tempExtractPath . DIRECTORY_SEPARATOR . $dbFileName;
            
            // If not found directly, search in the archive
            if (!file_exists($extractedDbPath)) {
                $files = scandir($tempExtractPath);
                $dbFiles = array_filter($files, function ($f) {
                    return strpos($f, '.sqlite') !== false || strpos($f, '.db') !== false;
                });

                if (empty($dbFiles)) {
                    throw new Exception('Database file not found in backup archive');
                }

                $extractedDbPath = $tempExtractPath . DIRECTORY_SEPARATOR . array_shift($dbFiles);
            }

            // Perform selective restore (preserve auth tables)
            $this->selectiveRestore($extractedDbPath, $dbPath);

            // Clean up temp extraction
            $this->deleteDirectory($tempExtractPath);

            // Reconnect to verify
            DB::reconnect();

            Log::info('Database restored from uploaded file (auth tables preserved)');

            return [
                'success' => true,
                'message' => 'Database berhasil direstore dari file (tabel user, role, permission tetap terjaga)',
            ];
        } catch (Exception $e) {
            Log::error('Database restore from file failed', ['error' => $e->getMessage()]);
            DB::reconnect();

            return [
                'success' => false,
                'message' => 'Gagal merestore database: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Selectively restore database tables (preserve auth-related tables)
     *
     * @param string $backupDbPath Path to backup database
     * @param string $currentDbPath Path to current database
     * @return void
     * @throws Exception
     */
    protected function selectiveRestore(string $backupDbPath, string $currentDbPath): void
    {
        $preserveTables = [
            'users',
            'roles',
            'permissions',
            'role_has_permissions',
            'model_has_roles',
            'model_has_permissions',
            'personal_access_tokens',
            'password_reset_tokens',
            'sessions',
        ];

        // Copy backup ke temp file (termasuk WAL/SHM jika ada)
        $tempBackupPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'restore_' . uniqid() . '.db';
        copy($backupDbPath, $tempBackupPath);

        foreach (['-wal', '-shm'] as $ext) {
            if (file_exists($backupDbPath . $ext)) {
                copy($backupDbPath . $ext, $tempBackupPath . $ext);
            }
        }

        $pdo = null;

        try {
            $pdo = new \PDO('sqlite:' . $currentDbPath);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->exec('PRAGMA foreign_keys = OFF');

            // ATTACH harus di luar transaksi
            $pdo->exec("ATTACH DATABASE '{$tempBackupPath}' AS backup_db");

            // Checkpoint WAL dari backup agar data terbaca sempurna
            $pdo->exec('PRAGMA backup_db.wal_checkpoint(TRUNCATE)');

            // Baca daftar tabel dari backup
            $allTables = $pdo->query(
                "SELECT name, sql FROM backup_db.sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"
            )->fetchAll(\PDO::FETCH_ASSOC);

            // Transaksi hanya untuk operasi write ke current DB
            $pdo->beginTransaction();

            try {
                foreach ($allTables as $tableInfo) {
                    $table = $tableInfo['name'];

                    if (in_array($table, $preserveTables)) {
                        continue;
                    }

                    $pdo->exec("DROP TABLE IF EXISTS `{$table}`");

                    if (!$tableInfo['sql']) {
                        continue;
                    }

                    $pdo->exec($tableInfo['sql']);

                    $cols = $pdo->query("PRAGMA backup_db.table_info('{$table}')")
                                ->fetchAll(\PDO::FETCH_ASSOC);

                    if (empty($cols)) {
                        continue;
                    }

                    $colStr = '`' . implode('`, `', array_column($cols, 'name')) . '`';
                    $pdo->exec("INSERT INTO `{$table}` ({$colStr}) SELECT {$colStr} FROM backup_db.`{$table}`");
                }

                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }

            // DETACH di luar transaksi
            $pdo->exec('DETACH DATABASE backup_db');
            $pdo->exec('PRAGMA foreign_keys = ON');
            $pdo = null;

        } finally {
            // Hapus temp file dan WAL/SHM-nya
            foreach (['', '-wal', '-shm'] as $ext) {
                if (file_exists($tempBackupPath . $ext)) {
                    unlink($tempBackupPath . $ext);
                }
            }
        }
    }

    /**
    {
        try {
            $this->ensureBackupDirExists();

            $backups = [];
            $metadataFiles = glob($this->backupDir . DIRECTORY_SEPARATOR . '*.json');

            foreach ($metadataFiles as $file) {
                $metadata = json_decode(file_get_contents($file), true);
                if ($metadata) {
                    $backups[] = $metadata;
                }
            }

            // Sort by timestamp descending (newest first)
            usort($backups, function ($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });

            return [
                'success' => true,
                'data' => $backups,
                'total' => count($backups),
            ];
        } catch (Exception $e) {
            Log::error('Failed to list backups', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar backup',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a specific backup
     *
     * @param string $backupName
     * @return array
     */
    public function deleteBackup(string $backupName): array
    {
        try {
            $zipFilePath = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.zip";
            $metadataFile = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.json";

            $deleted = false;

            if (file_exists($zipFilePath) && unlink($zipFilePath)) {
                $deleted = true;
            }

            if (file_exists($metadataFile) && unlink($metadataFile)) {
                $deleted = true;
            }

            if (!$deleted) {
                throw new Exception('Backup file not found');
            }

            Log::info('Backup deleted', ['backup' => $backupName]);

            return [
                'success' => true,
                'message' => 'Backup berhasil dihapus',
            ];
        } catch (Exception $e) {
            Log::error('Failed to delete backup', ['backup' => $backupName, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Gagal menghapus backup',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Download a specific backup file
     *
     * @param string $backupName
     * @return string|null
     */
    public function getBackupFilePath(string $backupName): ?string
    {
        $zipFilePath = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.zip";

        if (file_exists($zipFilePath)) {
            return $zipFilePath;
        }

        return null;
    }

    /**
     * Get backup metadata
     *
     * @param string $backupName
     * @return array|null
     */
    public function getBackupMetadata(string $backupName): ?array
    {
        $metadataFile = $this->backupDir . DIRECTORY_SEPARATOR . "{$backupName}.json";

        if (file_exists($metadataFile)) {
            return json_decode(file_get_contents($metadataFile), true);
        }

        return null;
    }

    /**
     * Generate README content for backup
     *
     * @param string $backupName
     * @param \Carbon\Carbon $timestamp
     * @param string|null $description
     * @return string
     */
    protected function generateReadmeContent(string $backupName, $timestamp, ?string $description = null): string
    {
        $content = "DATABASE BACKUP INFORMATION\n";
        $content .= "===========================\n\n";

        $content .= "Backup Name:\n";
        $content .= "  {$backupName}\n\n";

        $content .= "Created Date:\n";
        $content .= "  " . $timestamp->format('d M Y H:i:s') . " (UTC)\n";
        $content .= "  " . $timestamp->format('Y-m-d H:i:s') . "\n\n";

        if ($description) {
            $content .= "Description:\n";
            $content .= "  {$description}\n\n";
        }

        $content .= "Contents:\n";
        $content .= "  - README.txt (this file)\n";
        $content .= "  - Database file (SQLite database)\n\n";

        $content .= "Restore Instructions:\n";
        $content .= "  1. Extract this ZIP file\n";
        $content .= "  2. Use the 'Restore Database' feature in the application\n";
        $content .= "  3. Select this ZIP file to restore the database\n";
        $content .= "  4. The application will preserve user accounts, roles, and permissions\n\n";

        $content .= "Notes:\n";
        $content .= "  - This is an automated backup created by the system\n";
        $content .= "  - User accounts, roles, and permissions are preserved during restore\n";
        $content .= "  - A backup of the current database is created before restoring\n";

        return $content;
    }

    /**
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Recursively delete directory
     *
     * @param string $dir
     * @return bool
     */
    protected function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return true;
        }

        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($path)) {
                    $this->deleteDirectory($path);
                } else {
                    unlink($path);
                }
            }
        }

        return rmdir($dir);
    }

    /**
     * Get backup statistics
     *
     * @return array
     */
    public function getBackupStats(): array
    {
        try {
            $this->ensureBackupDirExists();

            $backups = $this->listBackups();

            if (!$backups['success']) {
                return [
                    'success' => false,
                    'total_backups' => 0,
                    'total_size' => 0,
                    'total_size_readable' => '0 B',
                ];
            }

            $totalSize = 0;
            foreach ($backups['data'] as $backup) {
                $totalSize += $backup['file_size'];
            }

            return [
                'success' => true,
                'total_backups' => count($backups['data']),
                'total_size' => $totalSize,
                'total_size_readable' => $this->formatBytes($totalSize),
                'latest_backup' => $backups['data'][0] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('Failed to get backup stats', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'total_backups' => 0,
                'total_size' => 0,
            ];
        }
    }
}
