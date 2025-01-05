<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
// define('BASE_URL', 'http://localhost:8000');
define('BASE_URL', 'https://localhost');
define('DEBUG_MODE', true);

// Error reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session configuration
ini_set('session.save_path', __DIR__ . '/../tmp/sessions');  
session_start();

// Autoloader function
spl_autoload_register(function ($class_name) {
    $paths = [
        'controllers/',
        'models/',
    ];
    
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});