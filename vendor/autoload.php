<?php

// Simple autoloader for Laravel 10
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = __DIR__ . "/../" . str_replace("\", "/", $class) . ".php";
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load Composer autoloader if it exists
if (file_exists(__DIR__ . "/composer/autoload_real.php")) {
    require_once __DIR__ . "/composer/autoload_real.php";
}
