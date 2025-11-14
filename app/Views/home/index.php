<?= view('layout/header') ?>

<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Assalamu'alaikum Warahmatullahi Wabarakatuh</h1>
            <p class="hero-subtitle">Selamat datang di Quiz Al-Qur'an</p>
            <p class="hero-description">
                Aplikasi untuk membaca Al-Qur'an digital lengkap dengan terjemahan bahasa Indonesia
                dan quiz interaktif untuk memperdalam pemahaman Anda tentang Al-Qur'an
            </p>
            <div class="hero-buttons">
                <a href="/quran" class="btn btn-primary">
                    <span>📖</span> Baca Al-Qur'an
                </a>
                <a href="/quiz" class="btn btn-secondary">
                    <span>✨</span> Mulai Quiz
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-number"><?= $total_surah ?? 114 ?></div>
            <div class="stat-label">Surah</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-number">6,236</div>
            <div class="stat-label">Ayat</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🎯</div>
            <div class="stat-number">4</div>
            <div class="stat-label">Jenis Quiz</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-number">Harian</div>
            <div class="stat-label">Quiz Baru</div>
        </div>
    </div>

    <!-- Featured Surahs -->
    <?php if (!empty($featured_surahs)): ?>
    <section class="section">
        <h2 class="section-title">Surah Pilihan</h2>
        <div class="surah-grid">
            <?php foreach ($featured_surahs as $surah): ?>
            <a href="/quran/surah/<?= $surah['number'] ?>" class="surah-card">
                <div class="surah-number"><?= $surah['number'] ?></div>
                <div class="surah-info">
                    <h3 class="surah-name-latin"><?= esc($surah['name_latin']) ?></h3>
                    <p class="surah-name-arabic"><?= $surah['name_arabic'] ?></p>
                    <p class="surah-meta">
                        <?= esc($surah['name_translation']) ?> •
                        <?= $surah['number_of_verses'] ?> Ayat •
                        <?= esc($surah['revelation']) ?>
                    </p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Quiz Types -->
    <section class="section">
        <h2 class="section-title">Jenis Quiz</h2>
        <div class="quiz-types-grid">
            <a href="/quiz/tebak-lanjutan" class="quiz-type-card">
                <div class="quiz-type-icon">📖</div>
                <h3>Tebak Lanjutan Ayat</h3>
                <p>Tebak kelanjutan dari ayat yang ditampilkan</p>
            </a>
            <a href="/quiz/tebak-surah" class="quiz-type-card">
                <div class="quiz-type-icon">🕌</div>
                <h3>Tebak Nama Surah</h3>
                <p>Tebak nama surah dari ayat yang ditampilkan</p>
            </a>
            <a href="/quiz/terjemahan" class="quiz-type-card">
                <div class="quiz-type-icon">🌙</div>
                <h3>Quiz Terjemahan</h3>
                <p>Cocokkan ayat dengan terjemahannya</p>
            </a>
            <a href="/quiz/daily" class="quiz-type-card featured">
                <div class="quiz-type-icon">⭐</div>
                <h3>Quiz Harian</h3>
                <p>Quiz harian dengan soal bervariasi</p>
                <span class="badge">Baru!</span>
            </a>
        </div>
    </section>

    <!-- Top Scorers -->
    <?php if (!empty($top_scorers)): ?>
    <section class="section">
        <h2 class="section-title">Top Scorers</h2>
        <div class="leaderboard-preview">
            <?php foreach (array_slice($top_scorers, 0, 5) as $index => $scorer): ?>
            <div class="leaderboard-item">
                <div class="rank">
                    <?php if ($index === 0): ?>
                        <span class="medal gold">🥇</span>
                    <?php elseif ($index === 1): ?>
                        <span class="medal silver">🥈</span>
                    <?php elseif ($index === 2): ?>
                        <span class="medal bronze">🥉</span>
                    <?php else: ?>
                        <span class="rank-number"><?= $index + 1 ?></span>
                    <?php endif; ?>
                </div>
                <div class="player-info">
                    <div class="player-name"><?= esc($scorer['username'] ?? 'Guest') ?></div>
                </div>
                <div class="score"><?= $scorer['score'] ?> pts</div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="/quiz/leaderboard" class="btn btn-outline">Lihat Semua</a>
        </div>
    </section>
    <?php endif; ?>
</div>

<?= view('layout/footer') ?>
