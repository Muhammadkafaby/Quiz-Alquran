/**
 * Toast Notification System
 * Provides beautiful, non-intrusive notifications for user feedback
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - Type of toast (success, error, warning, info)
     * @param {number} duration - Duration in milliseconds (default: 3000)
     */
    show(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        // Icon based on type
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || icons.info}</div>
            <div class="toast-message">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;

        this.container.appendChild(toast);

        // Trigger animation
        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        // Auto remove after duration
        setTimeout(() => {
            this.hide(toast);
        }, duration);

        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.hide(toast);
        });
    }

    hide(toast) {
        toast.classList.remove('toast-show');
        toast.classList.add('toast-hide');

        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    success(message, duration = 3000) {
        this.show(message, 'success', duration);
    }

    error(message, duration = 4000) {
        this.show(message, 'error', duration);
    }

    warning(message, duration = 3500) {
        this.show(message, 'warning', duration);
    }

    info(message, duration = 3000) {
        this.show(message, 'info', duration);
    }

    /**
     * Show a loading toast that persists until dismissed
     * @param {string} message - Loading message
     * @returns {HTMLElement} - The toast element to control later
     */
    loading(message = 'Memuat...') {
        const toast = document.createElement('div');
        toast.className = 'toast toast-loading';

        toast.innerHTML = `
            <div class="toast-spinner"></div>
            <div class="toast-message">${message}</div>
        `;

        this.container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        return toast;
    }

    /**
     * Dismiss a loading toast and optionally show a result
     * @param {HTMLElement} loadingToast - The loading toast element
     * @param {string} resultMessage - Optional result message
     * @param {string} resultType - Type of result (success or error)
     */
    dismissLoading(loadingToast, resultMessage = null, resultType = 'success') {
        if (loadingToast) {
            this.hide(loadingToast);
        }

        if (resultMessage) {
            setTimeout(() => {
                this.show(resultMessage, resultType);
            }, 300);
        }
    }

    /**
     * Show a toast with custom action button
     * @param {string} message - Message to display
     * @param {string} actionText - Text for action button
     * @param {function} actionCallback - Callback when action is clicked
     */
    showWithAction(message, actionText, actionCallback) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-action';

        toast.innerHTML = `
            <div class="toast-message">${message}</div>
            <button class="toast-action-btn">${actionText}</button>
            <button class="toast-close">×</button>
        `;

        this.container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        // Action button
        toast.querySelector('.toast-action-btn').addEventListener('click', () => {
            actionCallback();
            this.hide(toast);
        });

        // Close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.hide(toast);
        });

        // Auto remove after 5 seconds
        setTimeout(() => {
            this.hide(toast);
        }, 5000);
    }

    /**
     * Show achievement unlocked notification
     * @param {string} achievementName - Name of achievement
     * @param {string} achievementIcon - Icon/emoji for achievement
     * @param {number} points - Points earned
     */
    achievement(achievementName, achievementIcon, points) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-achievement';

        toast.innerHTML = `
            <div class="achievement-content">
                <div class="achievement-icon">${achievementIcon}</div>
                <div class="achievement-details">
                    <div class="achievement-title">Achievement Unlocked!</div>
                    <div class="achievement-name">${achievementName}</div>
                    <div class="achievement-points">+${points} poin</div>
                </div>
            </div>
        `;

        this.container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        // Auto remove after 5 seconds
        setTimeout(() => {
            this.hide(toast);
        }, 5000);

        // Play achievement sound if available
        this.playAchievementSound();
    }

    playAchievementSound() {
        // You can add a sound file here
        // const audio = new Audio('/sounds/achievement.mp3');
        // audio.play().catch(() => {});
    }
}

// Create global instance
const toast = new ToastNotification();

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ToastNotification;
}
