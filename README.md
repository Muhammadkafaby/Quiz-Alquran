# 🕌 Quiz Al-Qur'an - Aplikasi Web Islami

![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-orange)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-blue)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi web Islami berbasis CodeIgniter 4 untuk membaca Al-Qur'an digital dan belajar melalui quiz interaktif.

## ✨ Fitur Utama

### 📖 Pembaca Al-Qur'an
- **114 Surah Lengkap** - Semua surah dari Al-Fatihah hingga An-Nas
- **Teks Arab & Terjemahan** - Ayat dalam bahasa Arab dengan terjemahan bahasa Indonesia
- **Transliterasi Latin** - Memudahkan pembacaan bagi yang belum lancar membaca Arab
- **Tafsir Ringkas** - Penjelasan singkat untuk setiap surah dan ayat
- **Navigasi Mudah** - Berpindah antar ayat dan surah dengan lancar
- **Pencarian** - Cari ayat berdasarkan terjemahan

### 🎯 Quiz Interaktif
1. **Tebak Lanjutan Ayat**
   - Menebak kelanjutan dari ayat yang ditampilkan
   - Melatih hafalan ayat-ayat Al-Qur'an

2. **Tebak Nama Surah**
   - Menebak nama surah dari ayat yang ditampilkan
   - Mengenal surah-surah dalam Al-Qur'an

3. **Quiz Terjemahan**
   - Mencocokkan ayat Arab dengan terjemahannya
   - Meningkatkan pemahaman makna ayat

4. **Quiz Harian**
   - Quiz baru setiap hari dengan soal bervariasi
   - Mendorong konsistensi belajar harian

### 🏆 Fitur Tambahan

**Sistem Pengguna:**
- ✅ **Autentikasi** - Login, Register, Profile Management
- ✅ **Bookmark Ayat** - Simpan ayat favorit dengan catatan pribadi
- ✅ **Dark Mode** - Theme gelap untuk kenyamanan mata
- ✅ **Notifikasi** - Pengingat quiz harian dan achievement

**Fitur Interaktif:**
- ✅ **Audio Recitation** - Dengarkan murottal dari 9+ qari terkenal
- ✅ **Multiplayer Quiz** - Berkompetisi real-time dengan teman
- ✅ **Achievement System** - 16+ badges & pencapaian
- ✅ **Social Sharing** - Bagikan hasil ke WhatsApp, Twitter, Facebook, Telegram
- ✅ **Export PDF** - Download hasil quiz dalam format PDF
- ✅ **Leaderboard** - Ranking global dan per kategori

**Pengalaman Pengguna:**
- ✅ **Responsive Design** - Optimal di semua perangkat
- ✅ **Desain Islami** - Antarmuka indah dengan nuansa Islami
- ✅ **Progressive Features** - Offline capability (future)
- ✅ **Real-time Updates** - Live notification & multiplayer

## 🛠️ Teknologi

- **Framework**: CodeIgniter 4
- **PHP**: 8.0+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **API**: Quran API (https://api.quran.gading.dev)

## 📋 Persyaratan Sistem

- PHP 8.0 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web Server (Apache/Nginx)
- Extension PHP: intl, mbstring, json, mysqlnd

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/Quiz-Alquran.git
cd Quiz-Alquran
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
database.default.hostname = localhost
database.default.database = quiz_alquran
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
```

### 4. Buat Database

```bash
mysql -u root -p
CREATE DATABASE quiz_alquran;
exit;
```

### 5. Jalankan Migrasi

```bash
php spark migrate
```

### 6. Import Data Al-Qur'an

Akses halaman admin untuk import data:

```
http://localhost:8080/admin/import/quran
```

Atau gunakan command line (opsional):

```bash
php spark db:seed QuranSeeder
```

### 7. Jalankan Aplikasi

```bash
php spark serve
```

Buka browser dan akses: `http://localhost:8080`

## 📁 Struktur Folder

```
Quiz-Alquran/
├── app/
│   ├── Config/          # Konfigurasi aplikasi
│   ├── Controllers/     # Logic controllers
│   │   ├── Home.php
│   │   ├── QuranController.php
│   │   ├── QuizController.php
│   │   └── AdminController.php
│   ├── Models/          # Database models
│   │   ├── SurahModel.php
│   │   ├── AyahModel.php
│   │   ├── QuizResultModel.php
│   │   └── DailyQuizModel.php
│   ├── Views/           # Template views
│   │   ├── layout/
│   │   ├── home/
│   │   ├── quran/
│   │   └── quiz/
│   └── Database/
│       └── Migrations/  # Database migrations
├── public/
│   ├── css/            # Stylesheet files
│   ├── js/             # JavaScript files
│   └── images/         # Image assets
├── writable/           # Cache, logs, uploads
├── .env                # Environment configuration
├── composer.json       # Composer dependencies
└── README.md          # Documentation
```

## 🗄️ Struktur Database

### Tabel: `surah`
- **id**: Primary key
- **number**: Nomor surah (1-114)
- **name_arabic**: Nama surah dalam bahasa Arab
- **name_latin**: Nama surah dalam transliterasi latin
- **name_translation**: Arti nama surah
- **number_of_verses**: Jumlah ayat
- **revelation**: Tempat turun (Makkiyah/Madaniyah)
- **tafsir**: Tafsir ringkas

### Tabel: `ayah`
- **id**: Primary key
- **surah_id**: Foreign key ke tabel surah
- **number_in_surah**: Nomor ayat dalam surah
- **number_in_quran**: Nomor ayat dalam Al-Qur'an (1-6236)
- **text_arabic**: Teks ayat dalam bahasa Arab
- **text_latin**: Transliterasi latin
- **translation_id**: Terjemahan bahasa Indonesia
- **tafsir**: Tafsir ayat
- **juz**: Nomor juz
- **page**: Nomor halaman mushaf

### Tabel: `users`
- **id**: Primary key
- **username**: Username unik
- **email**: Email unik
- **password**: Password (hashed)
- **full_name**: Nama lengkap
- **total_score**: Total skor quiz
- **quiz_completed**: Jumlah quiz yang diselesaikan

### Tabel: `quiz_results`
- **id**: Primary key
- **user_id**: Foreign key ke users (nullable untuk guest)
- **quiz_type**: Jenis quiz (tebak_lanjutan, tebak_surah, terjemahan, daily)
- **total_questions**: Total soal
- **correct_answers**: Jawaban benar
- **score**: Skor yang didapat
- **time_taken**: Waktu pengerjaan (detik)
- **quiz_date**: Tanggal quiz
- **answers_data**: Data jawaban (JSON)

### Tabel: `daily_quiz`
- **id**: Primary key
- **quiz_date**: Tanggal quiz (unique)
- **questions_data**: Data soal (JSON)
- **total_questions**: Jumlah soal
- **theme**: Tema quiz harian

## 🎮 Cara Penggunaan

### Membaca Al-Qur'an
1. Klik menu **Al-Qur'an**
2. Pilih surah yang ingin dibaca
3. Scroll untuk membaca ayat per ayat
4. Gunakan navigasi untuk berpindah surah

### Mengikuti Quiz
1. Klik menu **Quiz**
2. Pilih jenis quiz yang diinginkan
3. Jawab semua pertanyaan
4. Klik "Selesai & Lihat Hasil"
5. Lihat skor dan pembahasan

### Melihat Leaderboard
1. Klik menu **Leaderboard**
2. Filter berdasarkan jenis quiz
3. Lihat ranking dan skor tertinggi

## 🔧 Konfigurasi

### Mengubah API Al-Qur'an

Edit file `.env`:

```env
quran.api.url = https://api.quran.gading.dev
```

### Mengubah Jumlah Soal Quiz

Edit controller `QuizController.php`:

```php
// Ubah parameter count
$questions = $this->generateTebakLanjutanQuestions(15); // dari 10 menjadi 15
```

### Mengubah Tema Warna

Edit file `public/css/style.css`:

```css
:root {
    --primary-color: #1a7f64;  /* Ubah warna primary */
    --secondary-color: #d4af37; /* Ubah warna secondary */
}
```

## 📱 Responsive Design

Aplikasi ini fully responsive dan telah dioptimasi untuk:
- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (< 768px)

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 🐛 Melaporkan Bug

Jika menemukan bug, silakan buat issue di GitHub dengan:
- Deskripsi bug
- Langkah-langkah reproduksi
- Screenshot (jika ada)
- Environment details

## 📝 To-Do List

- [ ] Sistem autentikasi user
- [ ] Fitur bookmark ayat
- [ ] Audio recitation
- [ ] Mode dark/light
- [ ] Notifikasi daily quiz
- [ ] Export hasil quiz ke PDF
- [ ] Multiplayer quiz mode
- [ ] Achievement system

## 📄 Lisensi

Aplikasi ini dilisensikan di bawah [MIT License](LICENSE).

## 🙏 Kredit

- **Al-Qur'an API**: [Quran API Indonesia](https://api.quran.gading.dev)
- **Framework**: [CodeIgniter 4](https://codeigniter.com)
- **Font Arab**: [Amiri Font](https://fonts.google.com/specimen/Amiri)
- **Icons**: Emoji Unicode

## 📞 Kontak

- **Developer**: Your Name
- **Email**: your.email@example.com
- **GitHub**: [@yourusername](https://github.com/yourusername)

## 🌟 Support

Jika aplikasi ini bermanfaat, berikan ⭐ di GitHub!

---

**Catatan**: Aplikasi ini dibuat untuk tujuan edukasi dan dakwah. Semoga bermanfaat untuk umat Islam dalam memperdalam Al-Qur'an.

**Barakallahu Fiikum** 🤲
