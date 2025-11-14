# 🚀 Panduan Instalasi Local - Quiz Al-Qur'an

## ✅ Framework CodeIgniter 4 Sudah Terinstall!

Framework CI4 sudah berhasil diinstall dan siap digunakan. Berikut panduan untuk menjalankan aplikasi di local.

## 📋 Persyaratan

- ✅ PHP 8.1 atau lebih tinggi
- ✅ Composer (sudah terinstall)
- ✅ MySQL/MariaDB
- ✅ Extension PHP: intl, mbstring, json, mysqlnd

## 🔧 Langkah Instalasi

### 1. Clone Repository (Jika Belum)

```bash
git clone <repository-url>
cd Quiz-Alquran
```

### 2. Install Dependencies (Sudah Selesai!)

```bash
composer install
```
✅ CodeIgniter 4 dan semua dependencies sudah terinstall!

### 3. Setup Database

#### Buat Database MySQL:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE quiz_alquran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### Atau gunakan phpMyAdmin:
1. Buka http://localhost/phpmyadmin
2. Klik "New"
3. Nama database: `quiz_alquran`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Create"

### 4. Konfigurasi Environment

File `.env` sudah ada. Edit jika perlu menyesuaikan dengan konfigurasi database Anda:

```bash
nano .env  # atau gunakan editor favorit
```

Pastikan konfigurasi database benar:

```env
database.default.hostname = localhost
database.default.database = quiz_alquran
database.default.username = root
database.default.password = your_password_here
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 5. Jalankan Migrasi Database

```bash
php spark migrate
```

Output yang diharapkan:
```
Running: 2024-01-01-000001_CreateSurahTable
Migrated: 2024-01-01-000001_CreateSurahTable
...
All migrations have been run.
```

### 6. Seed Achievements (Opsional tapi Direkomendasikan)

```bash
php spark db:seed AchievementSeeder
```

### 7. Import Data Al-Qur'an

Ada 2 cara:

#### Cara A: Via Browser (Mudah)
1. Jalankan server: `php spark serve`
2. Buka browser: http://localhost:8080/admin/import/quran
3. Tunggu proses selesai (±5-10 menit)

#### Cara B: Via Seeder (Jika tersedia)
```bash
php spark db:seed QuranSeeder
```

### 8. Jalankan Aplikasi

```bash
php spark serve
```

Atau dengan custom port:

```bash
php spark serve --host=0.0.0.0 --port=3000
```

Aplikasi akan berjalan di:
**http://localhost:8080**

## 🎯 Testing Aplikasi

### Test Command Line Interface:

```bash
# Lihat versi CI4
php spark --version

# Lihat semua routes
php spark routes

# Lihat status migrasi
php spark migrate:status

# Clear cache
php spark cache:clear
```

### Test di Browser:

1. **Homepage**: http://localhost:8080
2. **Al-Qur'an**: http://localhost:8080/quran
3. **Quiz**: http://localhost:8080/quiz
4. **Login**: http://localhost:8080/auth/login
5. **Register**: http://localhost:8080/auth/register

## 📁 Struktur Project

```
Quiz-Alquran/
├── app/
│   ├── Config/          # Konfigurasi CI4
│   ├── Controllers/     # Controllers
│   ├── Models/          # Models
│   ├── Views/           # Views/Templates
│   └── Database/
│       ├── Migrations/  # Database migrations
│       └── Seeds/       # Database seeders
├── public/              # Public files (CSS, JS, images)
├── vendor/              # ✅ Composer dependencies (CI4 installed here)
├── writable/            # Writable directories (cache, logs, uploads)
├── spark                # ✅ CI4 CLI tool
└── .env                 # Environment configuration
```

## 🛠️ Troubleshooting

### Error: Database connection failed

**Solusi:**
1. Pastikan MySQL running: `sudo service mysql start`
2. Cek kredensial database di `.env`
3. Test koneksi: `mysql -u root -p`

### Error: Permission denied pada writable/

**Solusi:**
```bash
chmod -R 755 writable/
chmod -R 755 public/
```

### Error: Class not found

**Solusi:**
```bash
composer dump-autoload
php spark cache:clear
```

### Port 8080 sudah digunakan

**Solusi:**
```bash
php spark serve --port=3000
```

## 📝 Perintah Berguna

```bash
# Start development server
php spark serve

# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Refresh migrations
php spark migrate:refresh

# Run seeder
php spark db:seed NamaSeeder

# Create new migration
php spark make:migration CreateTableName

# Create new model
php spark make:model ModelName

# Create new controller
php spark make:controller ControllerName

# Clear all caches
php spark cache:clear

# View all routes
php spark routes

# Check config
php spark config:check
```

## 🌐 Akses Fitur-Fitur

### Tanpa Login (Guest):
- ✅ Baca Al-Qur'an
- ✅ Ikuti Quiz (hasil tidak tersimpan)
- ✅ Lihat Leaderboard

### Dengan Login:
- ✅ Bookmark ayat favorit
- ✅ Simpan hasil quiz
- ✅ Achievement & Badges
- ✅ Notifikasi
- ✅ Multiplayer quiz
- ✅ Export PDF
- ✅ Profile management
- ✅ Dark mode preferences

## 🔐 Default Admin (Jika Ada)

Jika ingin membuat admin user:

```bash
php spark db:seed AdminSeeder
```

Default credentials:
- Email: admin@quiz-alquran.com
- Password: admin123

**⚠️ PENTING:** Ganti password setelah login pertama kali!

## 📊 Status Instalasi

| Component | Status |
|-----------|--------|
| Framework CI4 | ✅ Installed |
| Dependencies | ✅ Complete |
| Spark CLI | ✅ Working |
| Database | ⏳ Needs Setup |
| Migrations | ⏳ Needs Run |
| Al-Qur'an Data | ⏳ Needs Import |

## 🎉 Next Steps

1. ✅ Setup database
2. ✅ Run migrations
3. ✅ Import Al-Qur'an data
4. ✅ Test aplikasi di browser
5. ✅ Register akun baru
6. ✅ Explore fitur-fitur

## 💡 Tips

- Gunakan `php spark serve` untuk development
- Jangan lupa `composer dump-autoload` setelah add class baru
- Check logs di `writable/logs/` jika ada error
- Gunakan `.env` untuk konfigurasi, jangan hardcode
- Test fitur multiplayer dengan buka 2 browser berbeda

## 📞 Bantuan

Jika mengalami masalah:
1. Check dokumentasi: [CodeIgniter 4 Docs](https://codeigniter.com/user_guide/)
2. Lihat logs: `writable/logs/log-YYYY-MM-DD.log`
3. Clear cache: `php spark cache:clear`
4. Restart server

---

**Selamat menggunakan Quiz Al-Qur'an!** 🕌

Barakallahu Fiikum 🤲
