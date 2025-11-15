<!DOCTYPE html>
<html lang="id" data-theme="<?= session()->get('theme_preference') ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Quiz Al-Qur\'an') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/dark-mode.css">
    <link rel="stylesheet" href="/css/toast.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <div class="nav-brand">
                <a href="/">
                    <span class="nav-icon">🕌</span>
                    <span class="nav-title">Quiz Al-Qur'an</span>
                </a>
            </div>

            <ul class="nav-menu">
                <li><a href="/" class="nav-link">Beranda</a></li>
                <li><a href="/quran" class="nav-link">Al-Qur'an</a></li>
                <li><a href="/quiz" class="nav-link">Quiz</a></li>
                <li><a href="/multiplayer" class="nav-link">🎮 Multiplayer</a></li>
                <li><a href="/quiz/leaderboard" class="nav-link">🏆 Leaderboard</a></li>

                <?php if (session()->get('isLoggedIn')): ?>
                    <li><a href="/bookmark" class="nav-link">📌 Tersimpan</a></li>
                    <li><a href="/achievements" class="nav-link">🏅 Pencapaian</a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()" class="icon-btn" title="Toggle Dark Mode">
                    <span class="dark-mode-icon">🌙</span>
                </button>

                <?php if (session()->get('isLoggedIn')): ?>
                    <!-- Notifications -->
                    <div class="notification-dropdown">
                        <button onclick="toggleNotifications()" class="icon-btn notification-btn" title="Notifikasi">
                            <span>🔔</span>
                            <span class="notification-badge" id="notif-badge" style="display: none;">0</span>
                        </button>
                        <div class="dropdown-menu notification-menu" id="notification-menu">
                            <div class="dropdown-header">
                                <h3>Notifikasi</h3>
                                <button onclick="markAllAsRead()" class="btn-text">Tandai semua terbaca</button>
                            </div>
                            <div class="notification-list" id="notification-list">
                                <p class="empty-state-small">Tidak ada notifikasi</p>
                            </div>
                            <div class="dropdown-footer">
                                <a href="/notifications">Lihat semua</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="user-dropdown">
                        <button onclick="toggleUserMenu()" class="user-menu-btn">
                            <?php if (session()->get('avatar')): ?>
                                <img src="/writable/uploads/avatars/<?= esc(session()->get('avatar')) ?>" alt="Avatar" class="user-avatar">
                            <?php else: ?>
                                <div class="user-avatar-placeholder">
                                    <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="user-name"><?= esc(session()->get('username')) ?></span>
                            <span class="dropdown-arrow">▼</span>
                        </button>
                        <div class="dropdown-menu user-menu" id="user-menu">
                            <div class="dropdown-header">
                                <div class="user-info">
                                    <h4><?= esc(session()->get('full_name')) ?></h4>
                                    <p><?= esc(session()->get('email')) ?></p>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="/auth/profile" class="dropdown-item">
                                <span>👤</span> Profil Saya
                            </a>
                            <a href="/bookmark" class="dropdown-item">
                                <span>📌</span> Ayat Tersimpan
                            </a>
                            <a href="/achievements" class="dropdown-item">
                                <span>🏅</span> Pencapaian
                            </a>
                            <a href="/notifications" class="dropdown-item">
                                <span>🔔</span> Notifikasi
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/auth/logout" class="dropdown-item logout">
                                <span>🚪</span> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Login/Register Buttons -->
                    <a href="/auth/login" class="btn btn-outline btn-sm">Login</a>
                    <a href="/auth/register" class="btn btn-primary btn-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Audio Player (Global) -->
    <div id="audio-player" class="audio-player" style="display: none;">
        <div class="audio-controls">
            <button onclick="playPauseAudio()" class="audio-btn" id="playPauseBtn">
                <span>▶️</span>
            </button>
            <div class="audio-info">
                <div class="audio-title" id="audioTitle">Loading...</div>
                <div class="audio-progress">
                    <input type="range" id="audioSeek" min="0" max="100" value="0" class="audio-seek">
                    <div class="audio-time">
                        <span id="currentTime">0:00</span> / <span id="duration">0:00</span>
                    </div>
                </div>
            </div>
            <button onclick="closeAudioPlayer()" class="audio-close">✕</button>
        </div>
        <audio id="audioElement"></audio>
    </div>

    <main class="main-content">

<script>
// Dark Mode
function toggleDarkMode() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    // Update icon
    const icon = document.querySelector('.dark-mode-icon');
    icon.textContent = newTheme === 'dark' ? '☀️' : '🌙';

    // Save to server if logged in
    <?php if (session()->get('isLoggedIn')): ?>
    fetch('/auth/profile/update', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({theme_preference: newTheme})
    });
    <?php endif; ?>
}

// Initialize dark mode
window.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || '<?= session()->get('theme_preference') ?? 'light' ?>';
    document.documentElement.setAttribute('data-theme', savedTheme);

    const icon = document.querySelector('.dark-mode-icon');
    if (icon) {
        icon.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
    }

    <?php if (session()->get('isLoggedIn')): ?>
    // Load notification count
    loadNotificationCount();
    // Refresh every 30 seconds
    setInterval(loadNotificationCount, 30000);
    <?php endif; ?>
});

// Notifications
function toggleNotifications() {
    const menu = document.getElementById('notification-menu');
    menu.classList.toggle('show');

    if (menu.classList.contains('show')) {
        loadNotifications();
    }

    // Close user menu if open
    document.getElementById('user-menu').classList.remove('show');
}

function toggleUserMenu() {
    const menu = document.getElementById('user-menu');
    menu.classList.toggle('show');

    // Close notification menu if open
    document.getElementById('notification-menu')?.classList.remove('show');
}

function loadNotificationCount() {
    fetch('/notifications/unread-count')
        .then(res => res.json())
        .then(data => {
            const badge = document.getElementById('notif-badge');
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        });
}

function loadNotifications() {
    fetch('/notifications/')
        .then(res => res.text())
        .then(html => {
            // Parse and extract notification list
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const notifs = doc.querySelectorAll('.notification-item');

            const list = document.getElementById('notification-list');
            if (notifs.length > 0) {
                list.innerHTML = '';
                notifs.forEach((notif, i) => {
                    if (i < 5) { // Show only 5 in dropdown
                        list.appendChild(notif.cloneNode(true));
                    }
                });
            } else {
                list.innerHTML = '<p class="empty-state-small">Tidak ada notifikasi</p>';
            }
        });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {method: 'POST'})
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadNotificationCount();
                loadNotifications();
            }
        });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.notification-dropdown')) {
        document.getElementById('notification-menu')?.classList.remove('show');
    }
    if (!event.target.closest('.user-dropdown')) {
        document.getElementById('user-menu')?.classList.remove('show');
    }
});
</script>

<!-- Toast Notification System -->
<script src="/js/toast.js"></script>

<?php if (session()->getFlashdata('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toast.success('<?= esc(session()->getFlashdata('success')) ?>');
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toast.error('<?= esc(session()->getFlashdata('error')) ?>');
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('warning')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toast.warning('<?= esc(session()->getFlashdata('warning')) ?>');
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        toast.info('<?= esc(session()->getFlashdata('info')) ?>');
    });
</script>
<?php endif; ?>
