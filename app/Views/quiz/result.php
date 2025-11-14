<?= view('layout/header') ?>

<div class="result-container">
    <div class="result-header">
        <h1>Hasil Quiz</h1>
        <?php
        $emoji = '🎉';
        $message = 'Luar Biasa!';
        $class = 'excellent';

        if ($percentage < 50) {
            $emoji = '📚';
            $message = 'Terus Belajar!';
            $class = 'needs-improvement';
        } elseif ($percentage < 70) {
            $emoji = '👍';
            $message = 'Bagus!';
            $class = 'good';
        } elseif ($percentage < 90) {
            $emoji = '⭐';
            $message = 'Sangat Bagus!';
            $class = 'very-good';
        }
        ?>
        <div class="result-emoji"><?= $emoji ?></div>
        <h2 class="result-message <?= $class ?>"><?= $message ?></h2>
    </div>

    <div class="result-stats">
        <div class="stat-box">
            <div class="stat-value"><?= $result['score'] ?></div>
            <div class="stat-label">Skor</div>
        </div>
        <div class="stat-box">
            <div class="stat-value"><?= $result['correct_answers'] ?>/<?= $result['total_questions'] ?></div>
            <div class="stat-label">Benar</div>
        </div>
        <div class="stat-box">
            <div class="stat-value"><?= $percentage ?>%</div>
            <div class="stat-label">Akurasi</div>
        </div>
        <div class="stat-box">
            <div class="stat-value"><?= gmdate("i:s", $result['time_taken']) ?></div>
            <div class="stat-label">Waktu</div>
        </div>
    </div>

    <!-- Detailed Results -->
    <?php if (!empty($result['answers_data'])): ?>
    <div class="detailed-results">
        <h3>Detail Jawaban</h3>
        <?php foreach ($result['answers_data'] as $index => $answer): ?>
        <div class="answer-review <?= $answer['is_correct'] ? 'correct' : 'incorrect' ?>">
            <div class="answer-header">
                <span class="question-num">Soal <?= $index + 1 ?></span>
                <span class="answer-status">
                    <?= $answer['is_correct'] ? '✓ Benar' : '✗ Salah' ?>
                </span>
            </div>

            <div class="answer-content">
                <p class="question-text"><?= esc($answer['question']['question']) ?></p>

                <?php if (isset($answer['question']['verse_text'])): ?>
                <p class="verse-arabic-small"><?= $answer['question']['verse_text'] ?></p>
                <?php endif; ?>

                <div class="answer-options">
                    <?php foreach ($answer['question']['options'] as $option): ?>
                        <?php
                        $isUserAnswer = ($option['id'] == $answer['user_answer']);
                        $isCorrectAnswer = ($option['id'] == $answer['correct_answer']);
                        $optionClass = '';

                        if ($isCorrectAnswer) {
                            $optionClass = 'correct-option';
                        } elseif ($isUserAnswer && !$isCorrectAnswer) {
                            $optionClass = 'wrong-option';
                        }
                        ?>
                        <div class="option-review <?= $optionClass ?>">
                            <?php if ($answer['question']['type'] === 'tebak_lanjutan'): ?>
                                <?= $option['translation'] ?>
                            <?php elseif ($answer['question']['type'] === 'tebak_surah'): ?>
                                <?= esc($option['name']) ?>
                            <?php elseif ($answer['question']['type'] === 'terjemahan'): ?>
                                <?= esc($option['translation']) ?>
                            <?php endif; ?>

                            <?php if ($isCorrectAnswer): ?>
                                <span class="badge-correct">Jawaban Benar</span>
                            <?php elseif ($isUserAnswer): ?>
                                <span class="badge-wrong">Jawaban Anda</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="result-actions">
        <a href="/quiz" class="btn btn-primary">Quiz Lainnya</a>
        <a href="/quiz/leaderboard" class="btn btn-outline">Lihat Leaderboard</a>
        <a href="/" class="btn btn-outline">Kembali ke Beranda</a>
    </div>
</div>

<?= view('layout/footer') ?>
