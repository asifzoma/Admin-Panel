<?php
echo "Starting cleanup and setup...<br>";

// Clear Laravel log file
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    if (file_put_contents($logFile, '') !== false) {
        echo "Laravel log file cleared.<br>";
    } else {
        echo "Could not clear Laravel log file - check permissions.<br>";
    }
} else {
    echo "No Laravel log file found.<br>";
}

// Clear compiled views
$viewPath = __DIR__ . '/storage/framework/views';
if (is_dir($viewPath)) {
    array_map('unlink', glob("$viewPath/*"));
    echo "Compiled views cleared.<br>";
}

// Clear cache
$cachePath = __DIR__ . '/bootstrap/cache';
if (is_dir($cachePath)) {
    array_map('unlink', glob("$cachePath/*"));
    echo "Cache files cleared.<br>";
}

// Create SQLite database if it doesn't exist
$dbPath = __DIR__ . '/database/database.sqlite';
if (!file_exists($dbPath)) {
    if (touch($dbPath)) {
        chmod($dbPath, 0664);
        echo "SQLite database created.<br>";
    } else {
        echo "Failed to create SQLite database.<br>";
    }
}

// Create storage link if it doesn't exist
$publicStorage = __DIR__ . '/public/storage';
$storageApp = __DIR__ . '/storage/app/public';

if (!file_exists($publicStorage)) {
    if (symlink($storageApp, $publicStorage)) {
        echo "Storage link created.<br>";
    } else {
        echo "Failed to create storage link.<br>";
    }
}

// Set directory permissions
$dirsToChmod = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'database'
];

foreach ($dirsToChmod as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        chmod($path, 0775);
        echo "Set permissions for {$dir}.<br>";
    }
}

echo "Cleanup and setup completed!<br>";
echo "Next steps:<br>";
echo "1. Visit /simple-setup.php to initialize the database<br>";
echo "2. Delete cleanup-and-setup.php and simple-setup.php for security<br>";
echo "3. Rename .htaccess.bak files back to .htaccess<br>";
?>
