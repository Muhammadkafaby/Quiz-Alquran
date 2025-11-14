/**
 * Quiz Al-Qur'an - Main JavaScript File
 */

// Initialize application
document.addEventListener('DOMContentLoaded', function() {
    console.log('Quiz Al-Qur\'an Application Loaded');

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add active class to navigation links based on current page
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        if (currentPath.startsWith(linkPath) && linkPath !== '/') {
            link.classList.add('active');
        } else if (currentPath === '/' && linkPath === '/') {
            link.classList.add('active');
        }
    });
});

// Quiz Timer Functions
let quizStartTime = null;
let quizTimerInterval = null;

function startQuizTimer() {
    quizStartTime = Date.now();
    quizTimerInterval = setInterval(updateQuizTimer, 1000);
}

function updateQuizTimer() {
    if (!quizStartTime) return;

    const elapsed = Math.floor((Date.now() - quizStartTime) / 1000);
    const minutes = Math.floor(elapsed / 60);
    const seconds = elapsed % 60;

    const timerElement = document.getElementById('timer');
    if (timerElement) {
        timerElement.textContent =
            String(minutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }
}

function stopQuizTimer() {
    if (quizTimerInterval) {
        clearInterval(quizTimerInterval);
        quizTimerInterval = null;
    }
}

// Quiz Navigation Functions
let currentQuestionIndex = 0;

function showQuestion(index) {
    const questions = document.querySelectorAll('.question-card');
    questions.forEach((q, i) => {
        q.style.display = i === index ? 'block' : 'none';
    });

    // Update progress
    const currentElement = document.querySelector('.current-question');
    if (currentElement) {
        currentElement.textContent = index + 1;
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextQuestion() {
    const totalQuestions = document.querySelectorAll('.question-card').length;
    const currentCard = document.querySelectorAll('.question-card')[currentQuestionIndex];
    const selectedOption = currentCard.querySelector('input[type="radio"]:checked');

    if (!selectedOption) {
        alert('Pilih jawaban terlebih dahulu!');
        return;
    }

    if (currentQuestionIndex < totalQuestions - 1) {
        currentQuestionIndex++;
        showQuestion(currentQuestionIndex);
    }
}

function previousQuestion() {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--;
        showQuestion(currentQuestionIndex);
    }
}

// Form Validation
function validateQuizForm() {
    const questions = document.querySelectorAll('.question-card');
    let allAnswered = true;

    questions.forEach(question => {
        const selectedOption = question.querySelector('input[type="radio"]:checked');
        if (!selectedOption) {
            allAnswered = false;
        }
    });

    if (!allAnswered) {
        alert('Mohon jawab semua pertanyaan terlebih dahulu!');
        return false;
    }

    stopQuizTimer();
    return true;
}

// Copy to Clipboard (for sharing scores)
function copyToClipboard(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();

    try {
        document.execCommand('copy');
        alert('Berhasil disalin!');
    } catch (err) {
        console.error('Failed to copy:', err);
        alert('Gagal menyalin');
    }

    document.body.removeChild(textarea);
}

// Share Score Function
function shareScore(score, type) {
    const text = `Saya mendapat skor ${score} di Quiz Al-Qur'an (${type})! Coba juga di [URL]`;

    if (navigator.share) {
        navigator.share({
            title: 'Quiz Al-Qur\'an',
            text: text,
        }).catch(err => console.log('Error sharing:', err));
    } else {
        copyToClipboard(text);
    }
}

// Local Storage Functions for Guest Users
function saveQuizProgress(quizType, answers) {
    const progress = {
        type: quizType,
        answers: answers,
        timestamp: Date.now()
    };
    localStorage.setItem('quizProgress_' + quizType, JSON.stringify(progress));
}

function loadQuizProgress(quizType) {
    const stored = localStorage.getItem('quizProgress_' + quizType);
    if (stored) {
        return JSON.parse(stored);
    }
    return null;
}

function clearQuizProgress(quizType) {
    localStorage.removeItem('quizProgress_' + quizType);
}

// Audio Controls (if implementing audio recitation)
function playAyahAudio(surahNumber, ayahNumber) {
    // Placeholder for audio functionality
    console.log(`Playing audio for Surah ${surahNumber}, Ayah ${ayahNumber}`);
    // Can integrate with API like https://cdn.alquran.cloud/media/audio/ayah/ar.alafasy/
}

// Bookmark Ayah
function bookmarkAyah(surahId, ayahNumber) {
    let bookmarks = JSON.parse(localStorage.getItem('ayahBookmarks') || '[]');

    const bookmark = {
        surahId: surahId,
        ayahNumber: ayahNumber,
        timestamp: Date.now()
    };

    // Check if already bookmarked
    const exists = bookmarks.some(b =>
        b.surahId === surahId && b.ayahNumber === ayahNumber
    );

    if (!exists) {
        bookmarks.push(bookmark);
        localStorage.setItem('ayahBookmarks', JSON.stringify(bookmarks));
        alert('Ayat telah di-bookmark!');
    } else {
        alert('Ayat sudah di-bookmark sebelumnya');
    }
}

// Get Bookmarks
function getBookmarks() {
    return JSON.parse(localStorage.getItem('ayahBookmarks') || '[]');
}

// Keyboard Navigation for Quiz
document.addEventListener('keydown', function(e) {
    // Only for quiz pages
    if (!document.querySelector('.quiz-container')) return;

    // Arrow keys for navigation
    if (e.key === 'ArrowRight' || e.key === 'Enter') {
        const nextBtn = document.querySelector('.question-navigation .btn-primary:not([type="submit"])');
        if (nextBtn) {
            nextBtn.click();
        }
    } else if (e.key === 'ArrowLeft') {
        const prevBtn = document.querySelector('.question-navigation .btn-outline');
        if (prevBtn) {
            prevBtn.click();
        }
    }

    // Number keys (1-4) for selecting options
    if (e.key >= '1' && e.key <= '4') {
        const optionIndex = parseInt(e.key) - 1;
        const currentCard = document.querySelectorAll('.question-card')[currentQuestionIndex];
        const options = currentCard.querySelectorAll('input[type="radio"]');

        if (options[optionIndex]) {
            options[optionIndex].checked = true;
        }
    }
});

// Reading Progress Tracker
function saveReadingProgress(surahNumber, ayahNumber) {
    const progress = {
        surahNumber: surahNumber,
        ayahNumber: ayahNumber,
        timestamp: Date.now()
    };
    localStorage.setItem('readingProgress', JSON.stringify(progress));
}

function getReadingProgress() {
    const stored = localStorage.getItem('readingProgress');
    if (stored) {
        return JSON.parse(stored);
    }
    return null;
}

// Dark Mode Toggle (Optional feature)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark ? 'enabled' : 'disabled');
}

function loadDarkModePreference() {
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'enabled') {
        document.body.classList.add('dark-mode');
    }
}

// Initialize dark mode on load
loadDarkModePreference();

// Auto-save quiz answers (for accidental page refresh)
if (document.getElementById('quizForm')) {
    const form = document.getElementById('quizForm');
    const inputs = form.querySelectorAll('input[type="radio"]');

    inputs.forEach(input => {
        input.addEventListener('change', function() {
            const formData = new FormData(form);
            const answers = {};

            for (let [key, value] of formData.entries()) {
                answers[key] = value;
            }

            sessionStorage.setItem('currentQuizAnswers', JSON.stringify(answers));
        });
    });

    // Restore answers on page load
    const savedAnswers = sessionStorage.getItem('currentQuizAnswers');
    if (savedAnswers) {
        const answers = JSON.parse(savedAnswers);
        for (let [key, value] of Object.entries(answers)) {
            const input = form.querySelector(`input[name="${key}"][value="${value}"]`);
            if (input) {
                input.checked = true;
            }
        }
    }

    // Clear on submit
    form.addEventListener('submit', function() {
        sessionStorage.removeItem('currentQuizAnswers');
    });
}

// Print Result Function
function printResult() {
    window.print();
}

// Export utilities
window.QuizApp = {
    startQuizTimer,
    stopQuizTimer,
    nextQuestion,
    previousQuestion,
    validateQuizForm,
    shareScore,
    bookmarkAyah,
    saveReadingProgress,
    toggleDarkMode,
    printResult
};
