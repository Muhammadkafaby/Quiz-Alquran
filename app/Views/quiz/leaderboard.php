<?= view('layout/header') ?>

<div class="page-header">
    <div class="container">
        <h1 class="page-title">🏆 Leaderboard</h1>
        <p class="page-subtitle">Top Scorers Quiz Al-Qur'an</p>
    </div>
</div>

<div class="container">
    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <?php foreach ($types as $typeKey => $typeName): ?>
        <a href="/quiz/leaderboard<?= $typeKey ? '/' . $typeKey : '' ?>"
           class="filter-tab <?= $current_type == $typeKey ? 'active' : '' ?>">
            <?= esc($typeName) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Leaderboard List -->
    <div class="leaderboard-list">
        <?php if (!empty($leaderboard)): ?>
            <?php foreach ($leaderboard as $index => $entry): ?>
            <div class="leaderboard-card rank-<?= $index + 1 ?>">
                <div class="rank-display">
                    <?php if ($index === 0): ?>
                        <div class="medal gold">🥇</div>
                    <?php elseif ($index === 1): ?>
                        <div class="medal silver">🥈</div>
                    <?php elseif ($index === 2): ?>
                        <div class="medal bronze">🥉</div>
                    <?php else: ?>
                        <div class="rank-number"><?= $index + 1 ?></div>
                    <?php endif; ?>
                </div>

                <div class="player-details">
                    <div class="player-avatar">
                        <?php if (!empty($entry['avatar'])): ?>
                            <img src="<?= esc($entry['avatar']) ?>" alt="Avatar">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?= strtoupper(substr($entry['username'] ?? 'G', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="player-info-detail">
                        <h3 class="player-name"><?= esc($entry['username'] ?? 'Guest') ?></h3>
                        <p class="player-stats">
                            <?= $entry['correct_answers'] ?>/<?= $entry['total_questions'] ?> benar •
                            <?= gmdate("i:s", $entry['time_taken']) ?>
                        </p>
                    </div>
                </div>

                <div class="score-display">
                    <div class="score-value"><?= $entry['score'] ?></div>
                    <div class="score-label">poin</div>
                </div>

                <div class="quiz-info">
                    <span class="quiz-type-badge">
                        <?= ucwords(str_replace('_', ' ', $entry['quiz_type'])) ?>
                    </span>
                    <span class="quiz-date">
                        <?= date('d M Y', strtotime($entry['created_at'])) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <h3>Belum Ada Data</h3>
                <p>Jadilah yang pertama di leaderboard!</p>
                <a href="/quiz" class="btn btn-primary">Mulai Quiz</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Call to Action -->
    <div class="leaderboard-cta">
        <h2>Ingin Masuk Leaderboard?</h2>
        <p>Ikuti quiz dan raih skor tertinggi untuk masuk ke dalam daftar top scorers!</p>
        <a href="/quiz" class="btn btn-primary btn-lg">Ikuti Quiz Sekarang</a>
    </div>
</div>

<?= view('layout/footer') ?>
