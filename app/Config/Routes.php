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
    $routes->get('import/quran', 'AdminController::importQuran');
    $routes->get('import/status', 'AdminController::importStatus');
});
