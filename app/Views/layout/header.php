<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Quiz Al-Qur\'an') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
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
                <li><a href="/quiz/leaderboard" class="nav-link">Leaderboard</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
