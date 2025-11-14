<?= view('layout/header') ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">Quiz Al-Qur'an</h1>
        <p class="page-subtitle">Uji pemahaman Anda tentang Al-Qur'an</p>
    </div>
</div>

<div class="container">
    <div class="quiz-types-section">
        <?php if (!empty($quiz_types)): ?>
            <?php foreach ($quiz_types as $index => $type): ?>
            <div class="quiz-type-card-large <?= $index === count($quiz_types) - 1 ? 'featured' : '' ?>">
                <div class="quiz-type-icon-large"><?= $type['icon'] ?></div>
                <h2><?= esc($type['name']) ?></h2>
                <p><?= esc($type['description']) ?></p>
                <a href="<?= $type['url'] ?>" class="btn btn-primary btn-block">
                    Mulai Quiz
                </a>
                <?php if ($index === count($quiz_types) - 1): ?>
                <span class="badge-large">Quiz Baru Setiap Hari!</span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Instructions -->
    <div class="quiz-instructions">
        <h3>Cara Bermain</h3>
        <ol>
            <li>Pilih jenis quiz yang ingin Anda mainkan</li>
            <li>Jawab semua pertanyaan yang diberikan</li>
            <li>Setiap jawaban benar akan mendapat poin</li>
            <li>Lihat hasil dan skor Anda di akhir quiz</li>
            <li>Bandingkan skor Anda dengan pemain lain di leaderboard</li>
        </ol>
    </div>

    <!-- Quiz Benefits -->
    <div class="benefits-section">
        <h3>Manfaat Quiz Al-Qur'an</h3>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">📚</div>
                <h4>Meningkatkan Hafalan</h4>
                <p>Membantu mengingat ayat-ayat Al-Qur'an dengan lebih baik</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🧠</div>
                <h4>Memperdalam Pemahaman</h4>
                <p>Memahami makna dan konteks ayat-ayat suci</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">⭐</div>
                <h4>Belajar Sambil Bermain</h4>
                <p>Metode belajar yang menyenangkan dan interaktif</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h4>Tantangan Harian</h4>
                <p>Quiz baru setiap hari untuk konsistensi belajar</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="cta-section">
        <h2>Siap Memulai?</h2>
        <p>Pilih quiz favorit Anda dan mulai perjalanan memperdalam Al-Qur'an</p>
        <div class="cta-buttons">
            <a href="/quiz/daily" class="btn btn-primary btn-lg">
                ⭐ Coba Quiz Harian
            </a>
            <a href="/quiz/leaderboard" class="btn btn-outline btn-lg">
                🏆 Lihat Leaderboard
            </a>
        </div>
    </div>
</div>

<?= view('layout/footer') ?>
