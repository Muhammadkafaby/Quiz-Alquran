/**
 * Social Sharing Functionality
 */

class SocialShare {
    /**
     * Share quiz result
     */
    static shareQuizResult(score, quizType, totalQuestions, correctAnswers) {
        const percentage = Math.round((correctAnswers / totalQuestions) * 100);
        const emoji = percentage >= 90 ? '🌟' : percentage >= 70 ? '⭐' : '📚';

        const text = `${emoji} Saya mendapat skor ${score} (${percentage}%) di Quiz Al-Qur'an!\n\nJenis: ${this.formatQuizType(quizType)}\nBenar: ${correctAnswers}/${totalQuestions}\n\nIkuti juga di: ${window.location.origin}`;

        this.share(text, 'Quiz Al-Qur\'an');
    }

    /**
     * Share achievement
     */
    static shareAchievement(achievementName, achievementDescription) {
        const text = `🏅 Achievement Unlocked!\n\n${achievementName}\n${achievementDescription}\n\nQuiz Al-Qur'an - ${window.location.origin}`;

        this.share(text, 'Achievement Unlocked!');
    }

    /**
     * Share ayah
     */
    static shareAyah(surahName, ayahNumber, arabicText, translation) {
        const text = `📖 ${surahName} - Ayat ${ayahNumber}\n\n${arabicText}\n\nArtinya:\n${translation}\n\nBaca Al-Qur'an di: ${window.location.origin}/quran`;

        this.share(text, `${surahName} - Ayat ${ayahNumber}`);
    }

    /**
     * Generic share function
     */
    static share(text, title = 'Quiz Al-Qur\'an') {
        const url = window.location.href;

        // Use Web Share API if available (mobile)
        if (navigator.share) {
            navigator.share({
                title: title,
                text: text,
                url: url
            })
            .then(() => console.log('Shared successfully'))
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback: show share modal
            this.showShareModal(text, url);
        }
    }

    /**
     * Show share modal with options
     */
    static showShareModal(text, url) {
        const encodedText = encodeURIComponent(text);
        const encodedUrl = encodeURIComponent(url);

        const shareOptions = [
            {
                name: 'WhatsApp',
                icon: '💬',
                url: `https://wa.me/?text=${encodedText}`,
                color: '#25D366'
            },
            {
                name: 'Twitter',
                icon: '🐦',
                url: `https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}`,
                color: '#1DA1F2'
            },
            {
                name: 'Facebook',
                icon: '📘',
                url: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
                color: '#4267B2'
            },
            {
                name: 'Telegram',
                icon: '✈️',
                url: `https://t.me/share/url?url=${encodedUrl}&text=${encodedText}`,
                color: '#0088cc'
            },
            {
                name: 'Copy Link',
                icon: '🔗',
                action: () => this.copyToClipboard(text + '\n' + url),
                color: '#6c757d'
            }
        ];

        // Create modal HTML
        const modalHTML = `
            <div class="share-modal-overlay" id="shareModal" onclick="closeShareModal(event)">
                <div class="share-modal" onclick="event.stopPropagation()">
                    <div class="share-modal-header">
                        <h3>Bagikan</h3>
                        <button onclick="closeShareModal()" class="modal-close">✕</button>
                    </div>
                    <div class="share-options">
                        ${shareOptions.map(option => `
                            <button
                                class="share-option"
                                style="border-left: 4px solid ${option.color}"
                                onclick="${option.action ? option.action.toString() + '()' : `window.open('${option.url}', '_blank')`}"
                            >
                                <span class="share-icon">${option.icon}</span>
                                <span class="share-name">${option.name}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;

        // Add to body
        const modalDiv = document.createElement('div');
        modalDiv.innerHTML = modalHTML;
        document.body.appendChild(modalDiv);
    }

    /**
     * Copy to clipboard
     */
    static copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                this.showToast('✓ Link berhasil disalin!');
                closeShareModal();
            })
            .catch(() => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.showToast('✓ Link berhasil disalin!');
                closeShareModal();
            });
    }

    /**
     * Show toast notification
     */
    static showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    /**
     * Format quiz type
     */
    static formatQuizType(type) {
        const types = {
            'tebak_lanjutan': 'Tebak Lanjutan Ayat',
            'tebak_surah': 'Tebak Nama Surah',
            'terjemahan': 'Quiz Terjemahan',
            'daily': 'Quiz Harian'
        };
        return types[type] || type;
    }
}

// Close share modal
function closeShareModal(event) {
    if (event && event.target.id !== 'shareModal') return;

    const modal = document.getElementById('shareModal');
    if (modal) {
        modal.remove();
    }
}

// CSS for share modal (add to style.css or inject)
const shareStyles = `
<style>
.share-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.share-modal {
    background: white;
    border-radius: var(--radius-lg);
    max-width: 400px;
    width: 90%;
    box-shadow: var(--shadow-lg);
    animation: slideUp 0.3s ease;
}

[data-theme="dark"] .share-modal {
    background: #2d2d2d;
}

.share-modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.share-modal-header h3 {
    margin: 0;
    color: var(--text-dark);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-light);
    padding: 0;
    width: 32px;
    height: 32px;
}

.modal-close:hover {
    color: var(--danger-color);
}

.share-options {
    padding: 1rem;
}

.share-option {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    cursor: pointer;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
}

[data-theme="dark"] .share-option {
    background: #3d3d3d;
}

.share-option:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-sm);
}

.share-icon {
    font-size: 1.5rem;
}

.share-name {
    color: var(--text-dark);
    font-weight: 500;
}

.toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: #2d2d2d;
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    z-index: 10000;
    opacity: 0;
    transition: all 0.3s ease;
}

.toast.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
`;

// Inject styles
if (!document.getElementById('social-share-styles')) {
    const styleEl = document.createElement('div');
    styleEl.id = 'social-share-styles';
    styleEl.innerHTML = shareStyles;
    document.head.appendChild(styleEl);
}
