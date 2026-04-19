# Database Backup & Restore Guide

Panduan lengkap untuk menggunakan fitur backup dan restore database di aplikasi TRANSJB untuk deployment NativePhp.

## 📋 Daftar Isi

- [Ikhtisar](#ikhtisar)
- [Setup Awal](#setup-awal)
- [API Endpoints](#api-endpoints)
- [Penggunaan Command Line](#penggunaan-command-line)
- [Contoh Implementasi Frontend](#contoh-implementasi-frontend)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

## Ikhtisar

Fitur backup dan restore memungkinkan Anda untuk:

✓ Membuat backup database SQLite secara manual atau terjadwal
✓ Merestore database dari backup tertentu
✓ Melihat daftar semua backup yang tersedia
✓ Menghapus backup yang tidak diperlukan lagi
✓ Download backup file untuk disimpan di tempat lain
✓ Melacak riwayat backup dengan metadata lengkap

### Fitur Teknis

- **Format**: Database disimpan dalam format ZIP untuk hemat storage
- **Storage**: Semua backup tersimpan di `storage/backups/`
- **Metadata**: Setiap backup memiliki file metadata `.json` untuk informasi lengkap
- **Safety**: Sebelum restore, database lama disimpan sebagai backup otomatis
- **Logging**: Semua aktivitas backup tercatat di database dan log file

## Setup Awal

### 1. Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat table `database_backups` untuk melacak riwayat backup.

### 2. Buat Directory Backups

Directory `storage/backups/` akan dibuat otomatis saat membuat backup pertama.

### 3. Pastikan Permission Directory

Pastikan folder `storage/` memiliki write permission:

```bash
# Linux/Mac
chmod -R 755 storage/

# Windows (otomatis di NativePhp)
# Pastikan aplikasi dijalankan dengan user yang memiliki akses tulis
```

### 4. Konfigurasi (Opsional)

Anda dapat menambahkan konfigurasi di `.env` jika diperlukan:

```env
# Tidak ada konfigurasi spesifik untuk sekarang
# Semua setting sudah di-hardcode di DatabaseBackupService
```

## API Endpoints

Semua endpoint memerlukan autentikasi (`auth:sanctum`) dan role `admin`.

### 1. Membuat Backup

**Endpoint**: `POST /api/backups`

**Request**:
```json
{
  "description": "Backup sebelum update sistem (opsional)"
}
```

**Response Success (201)**:
```json
{
  "success": true,
  "message": "Backup berhasil dibuat",
  "data": {
    "backup_name": "2026-04-16_14-30-45",
    "zip_file": "2026-04-16_14-30-45.zip",
    "database_file": "database.sqlite",
    "timestamp": "2026-04-16T14:30:45+00:00",
    "date_readable": "16 Apr 2026 14:30:45",
    "file_size": 1048576,
    "file_size_readable": "1.00 MB",
    "description": "Backup sebelum update sistem",
    "status": "success"
  }
}
```

### 2. Melihat Daftar Backup

**Endpoint**: `GET /api/backups`

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "backup_name": "2026-04-16_14-30-45",
      "zip_file": "2026-04-16_14-30-45.zip",
      "database_file": "database.sqlite",
      "timestamp": "2026-04-16T14:30:45+00:00",
      "date_readable": "16 Apr 2026 14:30:45",
      "file_size": 1048576,
      "file_size_readable": "1.00 MB",
      "description": "Backup sebelum update sistem",
      "status": "success"
    }
  ],
  "total": 1
}
```

### 3. Mendapatkan Statistik Backup

**Endpoint**: `GET /api/backups/stats`

**Response**:
```json
{
  "success": true,
  "total_backups": 5,
  "total_size": 5242880,
  "total_size_readable": "5.00 MB",
  "latest_backup": {
    "backup_name": "2026-04-16_14-30-45",
    "zip_file": "2026-04-16_14-30-45.zip",
    "timestamp": "2026-04-16T14:30:45+00:00",
    "date_readable": "16 Apr 2026 14:30:45",
    "file_size": 1048576,
    "file_size_readable": "1.00 MB"
  }
}
```

### 4. Merestore Database

**Endpoint**: `POST /api/backups/restore`

**Request**:
```json
{
  "backup_name": "2026-04-16_14-30-45"
}
```

**Response Success**:
```json
{
  "success": true,
  "message": "Database berhasil direstore",
  "data": {
    "backup_name": "2026-04-16_14-30-45",
    "timestamp": "2026-04-16T14:30:45+00:00"
  }
}
```

⚠️ **PENTING**: Database lama akan disimpan sebagai backup otomatis sebelum restore dilakukan.

### 5. Mendapatkan Metadata Backup

**Endpoint**: `GET /api/backups/{backupName}/metadata`

**Response**:
```json
{
  "success": true,
  "data": {
    "backup_name": "2026-04-16_14-30-45",
    "zip_file": "2026-04-16_14-30-45.zip",
    "database_file": "database.sqlite",
    "timestamp": "2026-04-16T14:30:45+00:00",
    "date_readable": "16 Apr 2026 14:30:45",
    "file_size": 1048576,
    "file_size_readable": "1.00 MB",
    "description": "Backup sebelum update sistem",
    "status": "success"
  }
}
```

### 6. Download Backup

**Endpoint**: `GET /api/backups/{backupName}/download`

**Response**: File ZIP download

File akan di-download dengan nama `{backupName}.zip`

### 7. Menghapus Backup

**Endpoint**: `DELETE /api/backups`

**Request**:
```json
{
  "backup_name": "2026-04-16_14-30-45"
}
```

**Response Success**:
```json
{
  "success": true,
  "message": "Backup berhasil dihapus"
}
```

## Penggunaan Command Line

### Membuat Backup via Artisan Command

```bash
# Backup dengan deskripsi default
php artisan db:backup

# Backup dengan deskripsi custom
php artisan db:backup --description="Backup sebelum update important"

# Backup tipe terjadwal
php artisan db:backup --type=scheduled --description="Scheduled backup"
```

### Mengatur Backup Otomatis (Scheduler)

Edit file `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Backup database setiap hari jam 00:00
    $schedule->command('db:backup', ['--type=scheduled'])
        ->daily()
        ->at('00:00')
        ->appendOutputTo(storage_path('logs/backup-scheduler.log'));

    // Atau backup setiap jam
    $schedule->command('db:backup', ['--type=scheduled'])
        ->hourly()
        ->appendOutputTo(storage_path('logs/backup-scheduler.log'));
}
```

Kemudian jalankan scheduler:

```bash
# Development
php artisan schedule:work

# Production (tambahkan ke cron)
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

## Contoh Implementasi Frontend

### Menggunakan Axios (JavaScript/Vue)

```javascript
// Membuat Backup
async function createBackup(description = null) {
  try {
    const response = await axios.post('/api/backups', {
      description: description || null
    });
    console.log('Backup berhasil dibuat:', response.data.data);
    alert('Backup berhasil dibuat!');
    loadBackups(); // Reload list
  } catch (error) {
    console.error('Gagal membuat backup:', error.response.data);
    alert('Gagal membuat backup: ' + error.response.data.message);
  }
}

// Melihat Daftar Backup
async function loadBackups() {
  try {
    const response = await axios.get('/api/backups');
    console.log('Daftar backup:', response.data.data);
    // Render ke UI
  } catch (error) {
    console.error('Gagal mengambil backup:', error);
  }
}

// Mendapatkan Statistik
async function loadBackupStats() {
  try {
    const response = await axios.get('/api/backups/stats');
    console.log('Statistik backup:', response.data);
    return response.data;
  } catch (error) {
    console.error('Gagal mengambil statistik:', error);
  }
}

// Merestore Database
async function restoreDatabase(backupName) {
  if (!confirm('Apakah Anda yakin ingin merestore database? Data saat ini akan disimpan sebagai backup.')) {
    return;
  }

  try {
    const response = await axios.post('/api/backups/restore', {
      backup_name: backupName
    });
    console.log('Database berhasil direstore:', response.data);
    alert('Database berhasil direstore!');
  } catch (error) {
    console.error('Gagal merestore database:', error.response.data);
    alert('Gagal merestore database: ' + error.response.data.message);
  }
}

// Download Backup
function downloadBackup(backupName) {
  window.open(`/api/backups/${backupName}/download`, '_blank');
}

// Menghapus Backup
async function deleteBackup(backupName) {
  if (!confirm(`Hapus backup ${backupName}? Tindakan ini tidak dapat dibatalkan.`)) {
    return;
  }

  try {
    const response = await axios.delete('/api/backups', {
      data: { backup_name: backupName }
    });
    console.log('Backup berhasil dihapus:', response.data);
    alert('Backup berhasil dihapus!');
    loadBackups(); // Reload list
  } catch (error) {
    console.error('Gagal menghapus backup:', error.response.data);
    alert('Gagal menghapus backup: ' + error.response.data.message);
  }
}
```

### Blade Component (Laravel)

```blade
<div class="backup-manager">
    <div class="backup-stats">
        <h3>Statistik Backup</h3>
        <div id="stats">
            <p>Total: <span id="total-backups">-</span></p>
            <p>Ukuran: <span id="total-size">-</span></p>
        </div>
    </div>

    <div class="backup-actions">
        <button onclick="createBackup()" class="btn btn-primary">
            Buat Backup Sekarang
        </button>
    </div>

    <div class="backup-list">
        <h3>Daftar Backup</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Ukuran</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="backup-list">
                <!-- Diisi via JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<script>
// Jalankan saat halaman load
document.addEventListener('DOMContentLoaded', () => {
    loadBackupStats();
    loadBackups();
});

async function loadBackupStats() {
    const data = await axios.get('/api/backups/stats');
    document.getElementById('total-backups').textContent = data.data.total_backups;
    document.getElementById('total-size').textContent = data.data.total_size_readable;
}

async function loadBackups() {
    const response = await axios.get('/api/backups');
    const tbody = document.getElementById('backup-list');
    tbody.innerHTML = '';

    response.data.data.forEach(backup => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${backup.date_readable}</td>
            <td>${backup.file_size_readable}</td>
            <td>${backup.description || '-'}</td>
            <td>
                <button onclick="downloadBackup('${backup.backup_name}')" class="btn btn-sm btn-info">Download</button>
                <button onclick="restoreDatabase('${backup.backup_name}')" class="btn btn-sm btn-warning">Restore</button>
                <button onclick="deleteBackup('${backup.backup_name}')" class="btn btn-sm btn-danger">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}
</script>
```

## Best Practices

### 1. Backup Berkala

✓ Lakukan backup sebelum update sistem atau perubahan besar
✓ Gunakan scheduler untuk backup otomatis harian
✓ Simpan backup di lokasi aman (eksternal storage)

### 2. Penamaan Deskriptif

```
✓ "Backup sebelum update v2.0"
✓ "Backup sebelum migration data nasabah"
✗ "Backup123"
✗ "Test"
```

### 3. Manajemen Storage

- Monitor ukuran total backup
- Hapus backup lama yang tidak diperlukan
- Pertimbangkan untuk backup ke cloud atau eksternal storage

### 4. Testing Restore

- Lakukan test restore backup secara berkala
- Verifikasi bahwa data sudah benar setelah restore
- Dokumentasikan prosedur restore

### 5. Security

- Hanya admin yang dapat akses fitur backup/restore
- Simpan file backup di lokasi aman
- Download backup untuk disimpan offline

## Troubleshooting

### Error: "Database file not found"

**Penyebab**: File database SQLite tidak ditemukan pada lokasi yang dikonfigurasi

**Solusi**:
1. Pastikan database sudah di-setup dengan migration
2. Cek konfigurasi `DB_DATABASE` di `.env`
3. Pastikan path ke database file benar

```bash
php artisan tinker
> dd(database_path(config('database.connections.sqlite.database')))
```

### Error: "Failed to create zip archive"

**Penyebab**: Directory backups tidak ada atau tidak memiliki permission write

**Solusi**:
```bash
# Buat directory
mkdir -p storage/backups

# Set permission
chmod 755 storage/backups
```

### Error saat Restore: "Database locked"

**Penyebab**: Database masih diakses oleh process lain

**Solusi**:
1. Pastikan tidak ada request lain yang sedang berjalan
2. Tunggu beberapa saat dan coba lagi
3. Restart aplikasi jika perlu

### Backup File Terlalu Besar

**Penyebab**: Database terlalu besar atau backup tidak dikompres

**Solusi**:
1. Backup sudah dalam format ZIP (tersimpat secara otomatis)
2. Pertimbangkan untuk archive/delete old records dari database
3. Gunakan tool eksternal untuk kompres lebih lanjut

### Restore Gagal: "Invalid backup archive"

**Penyebab**: File backup corrupted

**Solusi**:
1. Cek integrity file ZIP: `unzip -t filename.zip`
2. Download backup lagi dari storage
3. Gunakan backup yang lebih lama
4. Restore manual dari backup eksternal

## File-file Terkait

- `app/Services/DatabaseBackupService.php` - Core service
- `app/Http/Controllers/Settings/BackupController.php` - API Controller
- `app/Models/DatabaseBackup.php` - Model untuk tracking
- `app/Console/Commands/DatabaseBackupCommand.php` - Artisan command
- `database/migrations/2026_04_16_140000_create_database_backups_table.php` - Migration
- `routes/api.php` - API Routes

## Catatan untuk NativePhp

Untuk deployment dengan NativePhp:

1. **Path Storage**: Di NativePhp, gunakan `storage_path()` untuk path file yang konsisten
2. **Background Task**: Anda dapat menjalankan backup via PHP CLI saat aplikasi startup/shutdown
3. **User Directory**: Pertimbangkan untuk menyimpan backup di user's document directory
4. **Permissions**: NativePhp menjalankan dengan user permissions, pastikan write access ke storage folder

```php
// Contoh di NativePhp Listener
\NativePhp\App::listen('ApplicationReady', function() {
    // Auto-backup saat app ready
    $backupService = app(DatabaseBackupService::class);
    $backupService->backup('Auto-backup on app startup');
});
```

---

**Terakhir diupdate**: 16 April 2026
