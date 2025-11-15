<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - Home page
$routes->get('/', 'Home::index');

// Quran routes
$routes->group('quran', function($routes) {
    $routes->get('/', 'QuranController::index');
    $routes->get('surah', 'QuranController::surahList');
    $routes->get('surah/(:num)', 'QuranController::surahDetail/$1');
    $routes->get('ayah/(:num)/(:num)', 'QuranController::ayahDetail/$1/$2');
});

// Quiz routes
$routes->group('quiz', function($routes) {
    $routes->get('/', 'QuizController::index');
    $routes->get('types', 'QuizController::quizTypes');

    // Quiz by type
    $routes->get('tebak-lanjutan', 'QuizController::tebakLanjutan');
    $routes->get('tebak-surah', 'QuizController::tebakSurah');
    $routes->get('terjemahan', 'QuizController::terjemahan');
    $routes->get('daily', 'QuizController::dailyQuiz');

    // Quiz submission
    $routes->post('submit', 'QuizController::submitQuiz');
    $routes->get('result/(:num)', 'QuizController::result/$1');

    // Leaderboard
    $routes->get('leaderboard', 'QuizController::leaderboard');
    $routes->get('leaderboard/(:alpha)', 'QuizController::leaderboard/$1');
});

// API routes for AJAX calls
$routes->group('api', function($routes) {
    $routes->get('surah', 'ApiController::getAllSurah');
    $routes->get('surah/(:num)', 'ApiController::getSurahById/$1');
    $routes->post('quiz/generate', 'ApiController::generateQuiz');
    $routes->post('quiz/check', 'ApiController::checkAnswer');
});

// Admin routes for importing data
$routes->group('admin', function($routes) {
    // Dashboard
    $routes->get('/', 'AdminDashboard::index');
    $routes->get('dashboard', 'AdminDashboard::index');

    // Management pages
    $routes->get('users', 'AdminDashboard::users');
    $routes->get('quizzes', 'AdminDashboard::quizzes');
    $routes->get('content', 'AdminDashboard::content');
    $routes->get('achievements', 'AdminDashboard::achievements');
    $routes->get('analytics', 'AdminDashboard::analytics');

    // Actions
    $routes->post('delete-user/(:num)', 'AdminDashboard::deleteUser/$1');
    $routes->get('export', 'AdminDashboard::export');

    // Data import (old routes)
    $routes->get('import/quran', 'AdminController::importQuran');
    $routes->get('import/status', 'AdminController::importStatus');
});

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::attemptRegister');
    $routes->get('logout', 'AuthController::logout');
    $routes->get('profile', 'AuthController::profile');
    $routes->post('profile/update', 'AuthController::updateProfile');
});

// Bookmark routes
$routes->group('bookmark', function($routes) {
    $routes->get('/', 'BookmarkController::index');
    $routes->post('toggle', 'BookmarkController::toggle');
    $routes->post('update-note/(:num)', 'BookmarkController::updateNote/$1');
    $routes->get('delete/(:num)', 'BookmarkController::delete/$1');
});

// Multiplayer routes
$routes->group('multiplayer', function($routes) {
    $routes->get('/', 'MultiplayerController::index');
    $routes->post('create-room', 'MultiplayerController::createRoom');
    $routes->post('join-room', 'MultiplayerController::joinRoom');
    $routes->get('room/(:alphanum)', 'MultiplayerController::room/$1');
    $routes->post('start-game', 'MultiplayerController::startGame');
    $routes->post('submit-answers', 'MultiplayerController::submitAnswers');
    $routes->get('room-status/(:num)', 'MultiplayerController::getRoomStatus/$1');
    $routes->get('leave/(:num)', 'MultiplayerController::leaveRoom/$1');
});

// PDF Export routes
$routes->group('pdf', function($routes) {
    $routes->get('result/(:num)', 'PDFController::exportResult/$1');
    $routes->get('statistics', 'PDFController::exportStatistics');
});

// Notification routes
$routes->group('notifications', function($routes) {
    $routes->get('/', 'NotificationController::index');
    $routes->post('mark-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('mark-all-read', 'NotificationController::markAllAsRead');
    $routes->get('unread-count', 'NotificationController::getUnreadCount');
});

// Achievement routes
$routes->group('achievements', function($routes) {
    $routes->get('/', 'AchievementController::index');
    $routes->get('user/(:num)', 'AchievementController::userAchievements/$1');
});
