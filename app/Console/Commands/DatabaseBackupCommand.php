<?php

namespace App\Console\Commands;

use App\Models\DatabaseBackup;
use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--description=} {--type=manual}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $backupService = app(DatabaseBackupService::class);
        $description = $this->option('description') ?? 'Backup created via command';
        $type = $this->option('type') ?? 'manual';

        $this->info('Creating database backup...');

        $result = $backupService->backup($description);

        if ($result['success']) {
            $data = $result['data'];

            // Log to database
            DatabaseBackup::create([
                'user_id' => null,
                'backup_name' => $data['backup_name'],
                'file_name' => $data['zip_file'],
                'file_size' => $data['file_size'],
                'description' => $description,
                'status' => 'success',
                'backup_type' => $type,
            ]);

            $this->info('✓ Backup created successfully!');
            $this->line('Backup name: ' . $data['backup_name']);
            $this->line('File size: ' . $data['file_size_readable']);
            $this->line('Location: storage/backups/');

            return self::SUCCESS;
        } else {
            $this->error('✗ Failed to create backup: ' . $result['message']);

            // Log failed backup to database
            DatabaseBackup::create([
                'backup_name' => 'failed-' . now()->timestamp,
                'file_name' => null,
                'file_size' => 0,
                'description' => $description,
                'status' => 'failed',
                'error_message' => $result['error'] ?? $result['message'],
                'backup_type' => $type,
            ]);

            return self::FAILURE;
        }
    }
}
