<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    /**
     * Clear application cache
     */
    public function clearCache(): JsonResponse
    {
        try {
            Artisan::call('optimize:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear Laravel log file
     */
    public function clearLog(): JsonResponse
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            
            if (File::exists($logPath)) {
                File::put($logPath, '');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Log file cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear log: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear both cache and log
     */
    public function clearAll(): JsonResponse
    {
        try {
            // Clear cache
            Artisan::call('optimize:clear');
            
            // Clear log
            $logPath = storage_path('logs/laravel.log');
            if (File::exists($logPath)) {
                File::put($logPath, '');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cache and log cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache and log: ' . $e->getMessage()
            ], 500);
        }
    }
}
