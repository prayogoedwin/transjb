# Backup & Restore - Quick Start Guide

Panduan cepat untuk menggunakan fitur backup dan restore database di aplikasi TRANSJB.

## 📦 File-file yang Ditambahkan

### Service Layer
- **`app/Services/DatabaseBackupService.php`** - Core service untuk backup dan restore operasi

### Controllers
- **`app/Http/Controllers/Settings/BackupController.php`** - API controller untuk backup endpoints

### Models
- **`app/Models/DatabaseBackup.php`** - Model untuk tracking riwayat backup

### Commands
- **`app/Console/Commands/DatabaseBackupCommand.php`** - Artisan command untuk backup manual atau terjadwal

### Views
- **`resources/views/settings/backup.blade.php`** - UI untuk manage backup

### Database
- **`database/migrations/2026_04_16_140000_create_database_backups_table.php`** - Migration untuk tabel backup history

### Documentation
- **`BACKUP_RESTORE_GUIDE.md`** - Dokumentasi lengkap

## 🚀 Setup Cepat

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Buka Web UI
Buka di browser: `http://localhost/settings/backup`

Atau akses via API: `GET /api/backups`

## 📱 Cara Menggunakan

### Via Web UI (Rekomendasi untuk User)

1. **Login dengan akun Admin**
2. **Buka Settings > Database Backup**
3. **Buat Backup:**
   - Isi deskripsi (opsional)
   - Klik "Buat Backup Sekarang"
4. **Melihat Backup:**
   - Daftar backup otomatis tampil
5. **Download Backup:**
   - Klik tombol "Download" untuk simpan backup file
6. **Restore Database:**
   - Klik tombol "Restore" untuk restore database
   - Konfirmasi action (data lama akan di-backup otomatis)
7. **Hapus Backup:**
   - Klik tombol "Hapus" untuk menghapus backup yang tidak perlu

### Via API

#### Membuat Backup
```bash
curl -X POST http://localhost/api/backups \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Backup sebelum update"
  }'
```

#### Melihat Daftar Backup
```bash
curl -X GET http://localhost/api/backups \
  -H "Authorization: Bearer YOUR_TOKEN"
```

#### Merestore Database
```bash
curl -X POST http://localhost/api/backups/restore \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "backup_name": "2026-04-16_14-30-45"
  }'
```

### Via Command Line

#### Backup Manual
```bash
php artisan db:backup --description="Backup sebelum update"
```

#### Setup Backup Otomatis
Edit `app/Console/Kernel.php` dan tambahkan:
```php
$schedule->command('db:backup', ['--type=scheduled'])
    ->daily()
    ->at('00:00');
```

Kemudian jalankan scheduler:
```bash
php artisan schedule:work
```

## 📂 File Storage Location

Semua backup disimpan di:
```
storage/backups/
├── 2026-04-16_14-30-45.zip          # Database file (compressed)
├── 2026-04-16_14-30-45.json         # Metadata
├── 2026-04-16_15-45-23.zip
└── 2026-04-16_15-45-23.json
```

Database history disimpan di:
```
database/database_backups table
```

## 🔒 Security & Permissions

- Hanya user dengan role `admin` yang dapat akses backup features
- Setiap backup action di-log ke database dengan user info
- Database lama otomatis di-backup sebelum restore

## ⚙️ API Endpoints (Admin Only)

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/backups` | Buat backup baru |
| GET | `/api/backups` | Daftar backup |
| GET | `/api/backups/stats` | Statistik backup |
| GET | `/api/backups/history` | Riwayat backup dari database |
| POST | `/api/backups/restore` | Restore dari backup |
| GET | `/api/backups/{name}/download` | Download backup file |
| GET | `/api/backups/{name}/metadata` | Ambil metadata backup |
| DELETE | `/api/backups` | Hapus backup |

## 🛠️ NativePhp Considerations

### Backup Folder Access
Di NativePhp, pastikan folder `storage/backups` accessible:
```php
// Di NativePhp listener
\NativePhp\App::listen('ApplicationReady', function() {
    // Ensure storage directory exists
    $backupDir = storage_path('backups');
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
});
```

### Auto-Backup on Startup
```php
// app/Console/Kernel.php atau NativePhp listener
use App\Services\DatabaseBackupService;

$backup = app(DatabaseBackupService::class);
$backup->backup('Auto-backup on startup');
```

### Export Backups to User Directory
```php
$backupService = app(DatabaseBackupService::class);
$backupFile = $backupService->getBackupFilePath($backupName);

// Copy to user's Documents
copy($backupFile, $_SERVER['DOCUMENT_ROOT'] . '/../Documents/backup.zip');
```

## 📊 Monitoring

### Check Backup Stats
```php
$backupService = app(DatabaseBackupService::class);
$stats = $backupService->getBackupStats();

echo "Total backups: " . $stats['total_backups'];
echo "Total size: " . $stats['total_size_readable'];
```

### View Backup History
```bash
# Via database
php artisan tinker
> App\Models\DatabaseBackup::latest()->limit(10)->get();

# Via database
SELECT * FROM database_backups ORDER BY created_at DESC LIMIT 10;
```

## 🚨 Common Issues

### "Database file not found"
- Pastikan migration sudah dijalankan: `php artisan migrate`
- Cek path database di config: `config('database.connections.sqlite.database')`

### "Storage directory not writable"
```bash
chmod -R 755 storage/
```

### Backup file terlalu besar
- Database sudah di-compress via ZIP
- Untuk hapus backup lama: Gunakan UI atau command `php artisan db:backup:delete {name}`

### Restore gagal
- Pastikan tidak ada process lain yang access database
- Tunggu beberapa saat dan coba lagi
- Cek file integrity: `unzip -t backup.zip`

## 📝 Best Practices

✅ **DO:**
- Lakukan backup sebelum update besar
- Gunakan deskripsi yang jelas untuk setiap backup
- Test restore backup secara berkala
- Simpan backup eksternal di lokasi aman
- Setup automated daily backups

❌ **DON'T:**
- Jangan hapus backup tanpa backup eksternal dulu
- Jangan restart aplikasi saat backup berjalan
- Jangan ubah file backup manual via file system
- Jangan gunakan backup dari source code berbeda

## 💡 Tips

1. **Backup ke Cloud**: Setelah download, simpan ke cloud (Google Drive, OneDrive, dll)
2. **Backup Multiple Versions**: Simpan beberapa versi backup untuk jaga-jaga
3. **Document Changes**: Catat perubahan apa saat membuat backup
4. **Regular Testing**: Test restore 1x per minggu di dev environment
5. **Monitoring**: Monitor storage usage dan cleanup backup lama

## 📞 Support

Untuk pertanyaan atau issue:
1. Cek dokumentasi lengkap: `BACKUP_RESTORE_GUIDE.md`
2. Lihat log file: `storage/logs/laravel.log`
3. Check database logs: Query `database_backups` table

---

**Version**: 1.0  
**Last Updated**: 16 April 2026
