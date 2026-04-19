<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\DatabaseBackup;
use App\Models\RestoreHistory;
use App\Services\DatabaseBackupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    protected DatabaseBackupService $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Create a new database backup
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function backup(Request $request): JsonResponse
    {
        $request->validate([
            'description' => 'nullable|string|max:500',
        ]);

        $result = $this->backupService->backup($request->input('description'));

        // Log to database
        if ($result['success']) {
            $data = $result['data'];
            DatabaseBackup::create([
                'user_id' => auth()->id(),
                'backup_name' => $data['backup_name'],
                'file_name' => $data['zip_file'],
                'file_size' => $data['file_size'],
                'description' => $request->input('description'),
                'status' => 'success',
                'backup_type' => 'manual',
            ]);
        } else {
            DatabaseBackup::create([
                'user_id' => auth()->id(),
                'backup_name' => 'failed-' . now()->timestamp,
                'file_name' => null,
                'file_size' => 0,
                'description' => $request->input('description'),
                'status' => 'failed',
                'error_message' => $result['error'] ?? $result['message'],
                'backup_type' => 'manual',
            ]);
        }

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    /**
     * Restore database from uploaded backup file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:2097152',
        ]);

        $file = $request->file('file');
        
        // Validate file extension
        if ($file->getClientOriginalExtension() !== 'zip') {
            return response()->json([
                'success' => false,
                'message' => 'File harus berformat ZIP',
            ], 422);
        }
        
        // Validate ZIP integrity
        $zip = new \ZipArchive();
        if ($zip->open($file->getPathname()) !== true) {
            return response()->json([
                'success' => false,
                'message' => 'File ZIP tidak valid atau rusak',
            ], 422);
        }
        $zip->close();

        $result = $this->backupService->restoreFromFile($file);

        // Log to restore history
        RestoreHistory::create([
            'user_id' => auth()->id(),
            'status' => $result['success'] ? 'success' : 'failed',
            'error_message' => $result['success'] ? null : ($result['error'] ?? $result['message']),
        ]);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Get list of all backups
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $result = $this->backupService->listBackups();

        return response()->json($result);
    }

    /**
     * Get backup statistics
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $result = $this->backupService->getBackupStats();

        return response()->json($result);
    }

    /**
     * Delete a specific backup
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'backup_name' => 'required|string',
        ]);

        $result = $this->backupService->deleteBackup($request->input('backup_name'));

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    /**
     * Download backup file
     *
     * @param string $backupName
     * @return BinaryFileResponse|JsonResponse
     */
    public function download(string $backupName)
    {
        $filePath = $this->backupService->getBackupFilePath($backupName);

        if (!$filePath) {
            return response()->json([
                'success' => false,
                'message' => 'Backup file not found',
            ], 404);
        }

        return response()->download($filePath, "{$backupName}.zip");
    }

    /**
     * Get backup metadata
     *
     * @param string $backupName
     * @return JsonResponse
     */
    public function metadata(string $backupName): JsonResponse
    {
        $metadata = $this->backupService->getBackupMetadata($backupName);

        if (!$metadata) {
            return response()->json([
                'success' => false,
                'message' => 'Backup not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $metadata,
        ]);
    }

    /**
     * Get backup history from database
     *
     * @return JsonResponse
     */
    public function history(): JsonResponse
    {
        $backups = DatabaseBackup::latest('created_at')
            ->with('user:id,name,email')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $backups,
        ]);
    }

    /**
     * Get restore history
     *
     * @return JsonResponse
     */
    public function restoreHistory(): JsonResponse
    {
        $history = RestoreHistory::latest('created_at')
            ->with('user:id,name,email')
            ->limit(20)
            ->get()
            ->map(function ($restore) {
                return [
                    'id' => $restore->id,
                    'status' => $restore->status,
                    'error_message' => $restore->error_message,
                    'date_readable' => $restore->date_readable,
                    'user_name' => $restore->user_name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }
}
