<?= view('layout/header') ?>

<div class="quiz-container">
    <div class="quiz-header">
        <h1><?= esc($title) ?></h1>
        <?php if (isset($theme)): ?>
        <p class="quiz-theme"><?= esc($theme) ?></p>
        <?php endif; ?>
        <div class="quiz-progress">
            <span class="current-question">1</span> / <span class="total-questions"><?= $total_questions ?></span>
        </div>
    </div>

    <form id="quizForm" method="post" action="/quiz/submit">
        <div class="questions-container">
            <?php if (!empty($questions)): ?>
                <?php foreach ($questions as $index => $question): ?>
                <div class="question-card" data-question="<?= $index + 1 ?>" style="<?= $index === 0 ? '' : 'display: none;' ?>">
                    <div class="question-header">
                        <span class="question-number">Pertanyaan <?= $index + 1 ?></span>
                    </div>

                    <div class="question-content">
                        <h3 class="question-text"><?= esc($question['question']) ?></h3>

                        <?php if (isset($question['verse_text'])): ?>
                        <div class="question-verse">
                            <p class="verse-arabic-quiz"><?= $question['verse_text'] ?></p>
                            <?php if (isset($question['verse_translation']) && $question['type'] !== 'terjemahan'): ?>
                            <p class="verse-translation-quiz"><?= esc($question['verse_translation']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="options-container">
                            <?php foreach ($question['options'] as $optIndex => $option): ?>
                            <label class="option-card">
                                <input type="radio" name="answers[<?= $index ?>]" value="<?= $option['id'] ?>" required>
                                <div class="option-content">
                                    <?php if ($question['type'] === 'tebak_lanjutan'): ?>
                                        <p class="option-arabic"><?= $option['text'] ?></p>
                                        <p class="option-translation"><?= esc($option['translation']) ?></p>
                                    <?php elseif ($question['type'] === 'tebak_surah'): ?>
                                        <p class="option-surah-name"><?= esc($option['name']) ?></p>
                                        <p class="option-surah-arabic"><?= $option['arabic'] ?></p>
                                    <?php elseif ($question['type'] === 'terjemahan'): ?>
                                        <p class="option-translation"><?= esc($option['translation']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <span class="option-check">✓</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="question-navigation">
                        <?php if ($index > 0): ?>
                        <button type="button" class="btn btn-outline" onclick="previousQuestion()">
                            ← Sebelumnya
                        </button>
                        <?php endif; ?>

                        <?php if ($index < count($questions) - 1): ?>
                        <button type="button" class="btn btn-primary" onclick="nextQuestion()">
                            Selanjutnya →
                        </button>
                        <?php else: ?>
                        <button type="submit" class="btn btn-success">
                            Selesai & Lihat Hasil
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </form>

    <div class="quiz-timer">
        <span class="timer-icon">⏱️</span>
        <span id="timer">00:00</span>
    </div>
</div>

<script>
let currentQuestionIndex = 0;
const totalQuestions = <?= $total_questions ?>;
let startTime = Date.now();
let timerInterval;

// Start timer
function startTimer() {
    timerInterval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        document.getElementById('timer').textContent =
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }, 1000);
}

function nextQuestion() {
    const currentCard = document.querySelector(`.question-card[data-question="${currentQuestionIndex + 1}"]`);
    const selectedOption = currentCard.querySelector('input[type="radio"]:checked');

    if (!selectedOption) {
        alert('Pilih jawaban terlebih dahulu!');
        return;
    }

    if (currentQuestionIndex < totalQuestions - 1) {
        currentCard.style.display = 'none';
        currentQuestionIndex++;
        const nextCard = document.querySelector(`.question-card[data-question="${currentQuestionIndex + 1}"]`);
        nextCard.style.display = 'block';
        updateProgress();
    }
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        const currentCard = document.querySelector(`.question-card[data-question="${currentQuestionIndex + 1}"]`);
        currentCard.style.display = 'none';
        currentQuestionIndex--;
        const prevCard = document.querySelector(`.question-card[data-question="${currentQuestionIndex + 1}"]`);
        prevCard.style.display = 'block';
        updateProgress();
    }
}

function updateProgress() {
    document.querySelector('.current-question').textContent = currentQuestionIndex + 1;
}

// Form submission
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const allAnswered = Array.from(document.querySelectorAll('.question-card')).every(card => {
        return card.querySelector('input[type="radio"]:checked') !== null;
    });

    if (!allAnswered) {
        e.preventDefault();
        alert('Mohon jawab semua pertanyaan terlebih dahulu!');
        return false;
    }

    clearInterval(timerInterval);
});

// Start timer when page loads
startTimer();
</script>

<?= view('layout/footer') ?>
