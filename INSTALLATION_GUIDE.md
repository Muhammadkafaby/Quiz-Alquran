# 📘 Panduan Instalasi Quiz Al-Qur'an

Dokumen ini berisi panduan lengkap instalasi aplikasi Quiz Al-Qur'an untuk berbagai environment.

## 📋 Daftar Isi

1. [Persiapan](#persiapan)
2. [Instalasi Lokal (Development)](#instalasi-lokal-development)
3. [Instalasi di Shared Hosting](#instalasi-di-shared-hosting)
4. [Instalasi di VPS](#instalasi-di-vps)
5. [Troubleshooting](#troubleshooting)

## Persiapan

### Requirements

**Minimum:**
- PHP 8.0 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx)

**PHP Extensions yang diperlukan:**
- intl
- mbstring
- json
- mysqlnd
- curl
- xml

**Cara cek PHP version:**
```bash
php -v
```

**Cara cek PHP extensions:**
```bash
php -m
```

## Instalasi Lokal (Development)

### 1. Install XAMPP/WAMP (Windows) atau LAMP (Linux)

**Windows:**
- Download [XAMPP](https://www.apachefriends.org/)
- Install dan jalankan Apache + MySQL

**Linux:**
```bash
sudo apt update
sudo apt install apache2 php mysql-server php-mysql php-intl php-mbstring php-json php-curl php-xml
```

**macOS:**
- Download [MAMP](https://www.mamp.info/)
- Atau gunakan Homebrew:
```bash
brew install php
brew install mysql
brew services start mysql
```

### 2. Install Composer

**Windows:**
- Download [Composer Installer](https://getcomposer.org/Composer-Setup.exe)

**Linux/macOS:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 3. Clone/Download Aplikasi

**Via Git:**
```bash
cd /path/to/htdocs  # atau /var/www/html di Linux
git clone https://github.com/username/Quiz-Alquran.git
cd Quiz-Alquran
```

**Via Download:**
1. Download ZIP dari GitHub
2. Extract ke folder `htdocs` (XAMPP) atau `/var/www/html` (Linux)

### 4. Install Dependencies

```bash
composer install
```

Jika error "composer not found", pastikan Composer sudah terinstall dan ada di PATH.

### 5. Setup Environment

```bash
cp .env.example .env
```

Edit file `.env`:
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/Quiz-Alquran/public/'

database.default.hostname = localhost
database.default.database = quiz_alquran
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 6. Buat Database

**Via phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Klik "New"
3. Nama database: `quiz_alquran`
4. Collation: `utf8mb4_unicode_ci`
5. Klik "Create"

**Via Command Line:**
```bash
mysql -u root -p
```
```sql
CREATE DATABASE quiz_alquran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
EXIT;
```

### 7. Jalankan Migrasi

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

### 8. Import Data Al-Qur'an

**Option A: Via Browser**
1. Jalankan server: `php spark serve`
2. Buka: http://localhost:8080/admin/import/quran
3. Tunggu hingga selesai (±5-10 menit)

**Option B: Via Command (jika tersedia seeder)**
```bash
php spark db:seed QuranSeeder
```

### 9. Jalankan Aplikasi

```bash
php spark serve
```

Buka browser: http://localhost:8080

## Instalasi di Shared Hosting

### 1. Persiapan File

Di komputer lokal:
```bash
# Install dependencies
composer install --no-dev

# Compress ke ZIP
zip -r quiz-alquran.zip . -x "*.git*" "writable/*" "vendor/*" ".env"
```

### 2. Upload ke Hosting

1. Login ke cPanel
2. Buka File Manager
3. Upload `quiz-alquran.zip` ke `public_html` atau folder lain
4. Extract file ZIP

### 3. Install Composer Dependencies

**Via SSH (jika tersedia):**
```bash
cd /home/username/public_html/Quiz-Alquran
composer install --no-dev
```

**Via cPanel Terminal:**
```bash
cd public_html/Quiz-Alquran
/opt/cpanel/composer/bin/composer install --no-dev
```

**Manual (jika tidak ada Composer):**
- Upload folder `vendor` yang sudah di-install di lokal

### 4. Setup Database

1. Login ke cPanel
2. Buka "MySQL Databases"
3. Buat database baru: `username_quizquran`
4. Buat user baru: `username_quizuser`
5. Set password yang kuat
6. Add user to database dengan ALL PRIVILEGES

### 5. Konfigurasi Environment

1. Rename `.env.example` menjadi `.env`
2. Edit `.env`:

```env
CI_ENVIRONMENT = production

app.baseURL = 'https://yourdomain.com/'

database.default.hostname = localhost
database.default.database = username_quizquran
database.default.username = username_quizuser
database.default.password = your_strong_password
database.default.DBDriver = MySQLi
```

### 6. Set Permissions

```bash
chmod -R 755 writable/
chmod -R 755 public/
```

### 7. Konfigurasi .htaccess

Buat file `.htaccess` di root folder (jika belum ada):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php/$1 [L]
</IfModule>
```

Buat file `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

### 8. Jalankan Migrasi

Via SSH:
```bash
php spark migrate
```

Via cPanel Terminal atau create manual via phpMyAdmin.

### 9. Import Data

Akses: https://yourdomain.com/admin/import/quran

## Instalasi di VPS

### 1. Setup Server (Ubuntu 20.04/22.04)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache, PHP, MySQL
sudo apt install apache2 php php-mysql php-intl php-mbstring php-json php-curl php-xml mysql-server -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Git
sudo apt install git -y
```

### 2. Konfigurasi MySQL

```bash
sudo mysql_secure_installation

# Login ke MySQL
sudo mysql -u root -p
```

```sql
CREATE DATABASE quiz_alquran CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'quizuser'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON quiz_alquran.* TO 'quizuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Clone Aplikasi

```bash
cd /var/www/html
sudo git clone https://github.com/username/Quiz-Alquran.git
cd Quiz-Alquran
sudo chown -R www-data:www-data .
```

### 4. Install Dependencies

```bash
composer install --no-dev
```

### 5. Konfigurasi Environment

```bash
cp .env.example .env
nano .env
```

```env
CI_ENVIRONMENT = production

app.baseURL = 'https://yourdomain.com/'

database.default.hostname = localhost
database.default.database = quiz_alquran
database.default.username = quizuser
database.default.password = strong_password_here
database.default.DBDriver = MySQLi
```

### 6. Set Permissions

```bash
sudo chmod -R 755 writable/
sudo chmod -R 755 public/
sudo chown -R www-data:www-data writable/
```

### 7. Konfigurasi Apache

```bash
sudo nano /etc/apache2/sites-available/quiz-alquran.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/Quiz-Alquran/public

    <Directory /var/www/html/Quiz-Alquran/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/quiz-error.log
    CustomLog ${APACHE_LOG_DIR}/quiz-access.log combined
</VirtualHost>
```

Enable site dan rewrite:
```bash
sudo a2ensite quiz-alquran.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 8. Install SSL (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### 9. Jalankan Migrasi

```bash
php spark migrate
```

### 10. Import Data

```bash
# Via browser
https://yourdomain.com/admin/import/quran

# Atau via CLI jika sudah ada seeder
php spark db:seed QuranSeeder
```

## Troubleshooting

### Error: "Database connection failed"

**Solusi:**
1. Cek kredensial database di `.env`
2. Pastikan MySQL service running: `sudo systemctl status mysql`
3. Test koneksi: `mysql -u username -p`

### Error: "File permission denied"

**Solusi:**
```bash
sudo chmod -R 755 writable/
sudo chown -R www-data:www-data writable/
```

### Error: "404 Not Found"

**Solusi:**
1. Cek `.htaccess` sudah benar
2. Enable mod_rewrite: `sudo a2enmod rewrite`
3. Restart Apache: `sudo systemctl restart apache2`

### Error: "Composer install failed"

**Solusi:**
```bash
# Clear composer cache
composer clear-cache

# Install dengan verbose
composer install -vvv

# Atau update composer
composer self-update
```

### Import Data Timeout

**Solusi:**
1. Tingkatkan `max_execution_time` di `php.ini`:
   ```ini
   max_execution_time = 300
   ```
2. Restart web server
3. Atau import via CLI jika tersedia

### Blank Page / White Screen

**Solusi:**
1. Set `CI_ENVIRONMENT = development` di `.env`
2. Cek error log: `writable/logs/`
3. Enable error display di `php.ini`:
   ```ini
   display_errors = On
   ```

## Support

Jika masih mengalami masalah:
- Baca dokumentasi CodeIgniter 4: https://codeigniter.com/user_guide/
- Create issue di GitHub
- Kontak developer

---

Selamat menggunakan Quiz Al-Qur'an! 🕌
