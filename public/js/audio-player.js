/**
 * Audio Player for Al-Qur'an Recitation
 * Supports multiple reciters from Alquran Cloud API
 */

class QuranAudioPlayer {
    constructor() {
        this.audio = document.getElementById('audioElement');
        this.player = document.getElementById('audio-player');
        this.playPauseBtn = document.getElementById('playPauseBtn');
        this.audioSeek = document.getElementById('audioSeek');
        this.currentTimeEl = document.getElementById('currentTime');
        this.durationEl = document.getElementById('duration');
        this.audioTitle = document.getElementById('audioTitle');

        this.currentSurah = null;
        this.currentAyah = null;
        this.reciter = localStorage.getItem('reciter') || 'ar.alafasy';

        this.init();
    }

    init() {
        if (!this.audio) return;

        // Audio event listeners
        this.audio.addEventListener('loadedmetadata', () => this.updateDuration());
        this.audio.addEventListener('timeupdate', () => this.updateProgress());
        this.audio.addEventListener('ended', () => this.onAudioEnded());
        this.audio.addEventListener('error', () => this.onAudioError());

        // Seek bar listener
        if (this.audioSeek) {
            this.audioSeek.addEventListener('input', (e) => this.seek(e.target.value));
        }
    }

    /**
     * Play ayah audio
     * @param {number} surahNumber - Surah number (1-114)
     * @param {number} ayahNumber - Ayah number in surah
     * @param {string} surahName - Surah name for display
     */
    playAyah(surahNumber, ayahNumber, surahName) {
        this.currentSurah = surahNumber;
        this.currentAyah = ayahNumber;

        // Calculate absolute ayah number
        const absoluteAyahNumber = this.getAbsoluteAyahNumber(surahNumber, ayahNumber);

        // Audio URL from Alquran Cloud
        const audioUrl = `https://cdn.islamic.network/quran/audio/128/${this.reciter}/${absoluteAyahNumber}.mp3`;

        this.audio.src = audioUrl;
        this.audioTitle.textContent = `${surahName} - Ayat ${ayahNumber}`;

        // Show player
        this.player.style.display = 'block';

        // Play
        this.audio.play()
            .then(() => {
                this.updatePlayPauseButton(true);
            })
            .catch(error => {
                console.error('Error playing audio:', error);
                this.onAudioError();
            });
    }

    /**
     * Play/Pause toggle
     */
    playPause() {
        if (this.audio.paused) {
            this.audio.play();
            this.updatePlayPauseButton(true);
        } else {
            this.audio.pause();
            this.updatePlayPauseButton(false);
        }
    }

    /**
     * Update play/pause button
     */
    updatePlayPauseButton(isPlaying) {
        if (this.playPauseBtn) {
            this.playPauseBtn.querySelector('span').textContent = isPlaying ? '⏸️' : '▶️';
        }
    }

    /**
     * Seek to position
     */
    seek(value) {
        const time = (value / 100) * this.audio.duration;
        this.audio.currentTime = time;
    }

    /**
     * Update progress bar
     */
    updateProgress() {
        if (this.audio.duration) {
            const progress = (this.audio.currentTime / this.audio.duration) * 100;
            this.audioSeek.value = progress;

            // Update time display
            this.currentTimeEl.textContent = this.formatTime(this.audio.currentTime);
        }
    }

    /**
     * Update duration display
     */
    updateDuration() {
        if (this.durationEl) {
            this.durationEl.textContent = this.formatTime(this.audio.duration);
        }
    }

    /**
     * Format time (seconds to mm:ss)
     */
    formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';

        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    /**
     * Handle audio ended
     */
    onAudioEnded() {
        this.updatePlayPauseButton(false);
        // Could auto-play next ayah here
    }

    /**
     * Handle audio error
     */
    onAudioError() {
        console.error('Audio loading error');
        this.audioTitle.textContent = 'Error memuat audio';
        this.updatePlayPauseButton(false);
    }

    /**
     * Close player
     */
    close() {
        this.audio.pause();
        this.audio.src = '';
        this.player.style.display = 'none';
        this.updatePlayPauseButton(false);
    }

    /**
     * Change reciter
     */
    changeReciter(reciterCode) {
        this.reciter = reciterCode;
        localStorage.setItem('reciter', reciterCode);

        // Reload current ayah if playing
        if (this.currentSurah && this.currentAyah) {
            this.playAyah(this.currentSurah, this.currentAyah, this.audioTitle.textContent.split(' - ')[0]);
        }
    }

    /**
     * Get absolute ayah number (1-6236)
     * This is a simplified version - you should use the actual number from database
     */
    getAbsoluteAyahNumber(surahNumber, ayahNumber) {
        // This would normally query the database
        // For now, use an approximation based on surah verses
        const versesBeforeSurah = [
            0, 7, 293, 493, 669, 789, 954, 1160, 1235, 1364,
            1473, 1596, 1707, 1750, 1802, 1901, 2029, 2140, 2250,
            2348, 2483, 2595, 2673, 2791, 2855, 2932, 3159, 3252,
            3340, 3409, 3469, 3503, 3533, 3606, 3660, 3705, 3788,
            3970, 4058, 4133, 4218, 4272, 4325, 4414, 4473, 4510,
            4545, 4583, 4612, 4630, 4675, 4735, 4784, 4846, 4901,
            4979, 5075, 5104, 5126, 5150, 5163, 5177, 5188, 5199,
            5217, 5229, 5241, 5271, 5323, 5375, 5419, 5447, 5475,
            5495, 5551, 5591, 5622, 5672, 5712, 5758, 5800, 5829,
            5848, 5884, 5909, 5931, 5948, 5967, 5993, 6023, 6043,
            6058, 6079, 6090, 6098, 6106, 6125, 6130, 6138, 6146,
            6157, 6168, 6176, 6185, 6193, 6197, 6204, 6207, 6213,
            6216, 6221, 6225, 6230
        ];

        return versesBeforeSurah[surahNumber - 1] + ayahNumber;
    }
}

// Initialize player
let quranPlayer;
document.addEventListener('DOMContentLoaded', function() {
    quranPlayer = new QuranAudioPlayer();
});

// Global functions for easy access
function playAyahAudio(surahNumber, ayahNumber, surahName) {
    if (quranPlayer) {
        quranPlayer.playAyah(surahNumber, ayahNumber, surahName);
    }
}

function playPauseAudio() {
    if (quranPlayer) {
        quranPlayer.playPause();
    }
}

function closeAudioPlayer() {
    if (quranPlayer) {
        quranPlayer.close();
    }
}

function changeReciter(reciterCode) {
    if (quranPlayer) {
        quranPlayer.changeReciter(reciterCode);
    }
}

// Available reciters
const RECITERS = {
    'ar.alafasy': 'Mishary Rashid Alafasy',
    'ar.abdulbasitmurattal': 'Abdul Basit (Murattal)',
    'ar.abdulsamad': 'Abdul Basit (Mujawwad)',
    'ar.shaatree': 'Abu Bakr Al-Shatri',
    'ar.husary': 'Mahmoud Khalil Al-Hussary',
    'ar.minshawi': 'Mohamed Siddiq El-Minshawi',
    'ar.muhammadayyoub': 'Muhammad Ayyub',
    'ar.hudhaify': 'Ali Jaber',
    'ar.mahermuaiqly': 'Maher Al-Muaiqly'
};
