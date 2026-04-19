<x-layouts.app>
    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <x-icons.chevron-right />
        <span class="text-gray-500 dark:text-gray-400">Database Backup</span>
    </div>

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Database Backup & Restore</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Kelola backup database dan lakukan restore jika diperlukan
        </p>
    </div>

    <div class="p-6" x-data="backupManager()" @load="init()">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Backup Content -->
            <div class="flex-1">
                <!-- Actions Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Buat Backup</h3>
                        <form @submit.prevent="createBackup()" class="space-y-4">
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Deskripsi (Opsional)
                                </label>
                                <textarea id="description" x-model="formData.description"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="Contoh: Backup sebelum update sistem"
                                    rows="2"></textarea>
                            </div>
                            <button type="submit" :disabled="loading"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                <span x-show="!loading">Buat Backup Sekarang</span>
                                <span x-show="loading">Membuat backup...</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Restore History Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Riwayat Restore</h3>
                    </div>

                    <!-- Loading State -->
                    <div x-show="historyLoading" class="p-6 text-center text-gray-600 dark:text-gray-400">
                        <p>Memuat riwayat restore...</p>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!historyLoading && restoreHistory.length === 0" class="p-6 text-center text-gray-600 dark:text-gray-400">
                        <p>Belum ada riwayat restore</p>
                    </div>

                    <!-- Restore History List -->
                    <div x-show="!historyLoading && restoreHistory.length > 0" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal Restore</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">User</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="restore in restoreHistory" :key="restore.id">
                                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100" x-text="restore.date_readable"></td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400" x-text="restore.user_name || '-'"></td>
                                        <td class="px-6 py-4 text-sm">
                                            <span :class="restore.status === 'success' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'" class="px-2 py-1 rounded text-xs font-medium" x-text="restore.status === 'success' ? 'Berhasil' : 'Gagal'"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Alert Messages -->
                <div x-show="alert.message" class="mt-4 p-4 rounded-lg" :class="alert.type === 'success' ? 'bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-700 dark:text-green-100' : 'bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-100'">
                    <p x-text="alert.message"></p>
                </div>

                <!-- Restore Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-6">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Restore Database</h3>
                        <form @submit.prevent="restoreFromFile()" class="space-y-4">
                            <div>
                                <label for="restoreFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih File Backup untuk Di-restore
                                </label>
                                <input type="file" id="restoreFile" accept=".zip"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"
                                    required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format file harus ZIP yang sebelumnya di-download dari fitur backup</p>
                            </div>
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    ⚠️ <strong>Perhatian:</strong> Restore akan mengganti semua data database dengan data dari file backup. Data saat ini akan disimpan sebagai backup otomatis.
                                </p>
                            </div>
                            <button type="submit" :disabled="loading"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium">
                                <span x-show="!loading">Restore Sekarang</span>
                                <span x-show="loading">Melakukan restore...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.backupManager = function() {
            return {
                backups: [],
                stats: {},
                loading: false,
                historyLoading: false,
                restoreHistory: [],
                formData: {
                    description: ''
                },
                alert: {
                    message: '',
                    type: 'success'
                },

                async init() {
                    // await this.loadStats();
                    // await this.loadBackups();
                    await this.loadRestoreHistory();
                },

                // async loadStats() {
                //     try {
                //         const response = await axios.get('/api/backups/stats');
                //         this.stats = response.data.data;
                //     } catch (error) {
                //         console.error('Failed to load stats:', error);
                //     }
                // },

                // async loadBackups() {
                //     try {
                //         this.loading = true;
                //         const response = await axios.get('/api/backups');
                //         this.backups = response.data.data;
                //     } catch (error) {
                //         console.error('Failed to load backups:', error);
                //         this.showAlert('Gagal memuat daftar backup', 'error');
                //     } finally {
                //         this.loading = false;
                //     }
                // },

                async createBackup() {
                    try {
                        this.loading = true;
                        const response = await axios.post('/api/backups', {
                            description: this.formData.description || null
                        });
                        this.showAlert('Backup berhasil dibuat', 'success');
                        this.formData.description = '';
                        // await this.loadBackups();
                        // await this.loadStats();
                        
                        // Auto-download the backup
                        if (response.data.data && response.data.data.backup_name) {
                            await new Promise(resolve => setTimeout(resolve, 500)); // Brief delay
                            await this.downloadBackup(response.data.data.backup_name);
                        }
                    } catch (error) {
                        console.error('Failed to create backup:', error);
                        this.showAlert(error.response?.data?.message || 'Gagal membuat backup', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async loadRestoreHistory() {
                    try {
                        this.historyLoading = true;
                        const response = await axios.get('/api/backups/restore-history');
                        this.restoreHistory = response.data.data || [];
                    } catch (error) {
                        console.error('Failed to load restore history:', error);
                    } finally {
                        this.historyLoading = false;
                    }
                },

                async restoreFromFile() {
                    const fileInput = document.getElementById('restoreFile');
                    const file = fileInput?.files?.[0];
                    
                    if (!file) {
                        this.showAlert('Pilih file backup terlebih dahulu', 'error');
                        return;
                    }

                    if (!confirm('Apakah Anda yakin ingin merestore database dari file ini? Tindakan ini tidak dapat dibatalkan.')) {
                        return;
                    }

                    try {
                        this.loading = true;
                        const formData = new FormData();
                        formData.append('file', file);
                        
                        const response = await axios.post('/api/backups/restore', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });
                        this.showAlert('Database berhasil direstore', 'success');
                        
                        // Reset file input properly
                        fileInput.type = 'text';
                        fileInput.type = 'file';
                        
                        // await this.loadBackups();
                        // await this.loadStats();
                        await this.loadRestoreHistory();
                    } catch (error) {
                        console.error('Failed to restore backup:', error);
                        
                        // Handle validation errors
                        let errorMessage = 'Gagal merestore database';
                        if (error.response?.data?.errors?.file) {
                            // Validation errors
                            errorMessage = error.response.data.errors.file[0] || errorMessage;
                        } else if (error.response?.data?.message) {
                            errorMessage = error.response.data.message;
                        }
                        
                        this.showAlert(errorMessage, 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async downloadBackup(backupName) {
                    try {
                        this.loading = true;
                        const response = await axios.get(`/api/backups/${backupName}/download`, {
                            responseType: 'blob'
                        });
                        
                        // Create blob URL and trigger download
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `${backupName}.zip`);
                        document.body.appendChild(link);
                        link.click();
                        link.parentNode.removeChild(link);
                        window.URL.revokeObjectURL(url);
                        
                        this.showAlert('Backup berhasil diunduh', 'success');
                    } catch (error) {
                        console.error('Failed to download backup:', error);
                        this.showAlert(error.response?.data?.message || 'Gagal mengunduh backup', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                async deleteBackup(backupName) {
                    if (!confirm(`Hapus backup ${backupName}? Tindakan ini tidak dapat dibatalkan.`)) {
                        return;
                    }

                    try {
                        this.loading = true;
                        await axios.delete('/api/backups', {
                            data: { backup_name: backupName }
                        });
                        this.showAlert('Backup berhasil dihapus', 'success');
                        // await this.loadBackups();
                        // await this.loadStats();
                    } catch (error) {
                        console.error('Failed to delete backup:', error);
                        this.showAlert(error.response?.data?.message || 'Gagal menghapus backup', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                showAlert(message, type) {
                    this.alert.message = message;
                    this.alert.type = type;
                    setTimeout(() => {
                        this.alert.message = '';
                    }, 5000);
                }
            }
        }
    </script>
</x-layouts.app>
