<?php

/**
 * CodeIgniter 4 - Quiz Al-Qur'an Application
 * Front Controller
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
chdir(FCPATH);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 */

// Load our paths config file
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();

// Location of the framework bootstrap file.
require $paths->systemDirectory . '/bootstrap.php';

// Load environment settings from .env files
require $paths->systemDirectory . '/Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

/*
 * ---------------------------------------------------------------
 * GRAB OUR CODEIGNITER INSTANCE
 * ---------------------------------------------------------------
 */
$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 */
$app->run();
