<?php
// Development error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Site configuration
define('BASE_URL', '/g6glp');
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/permissions.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
