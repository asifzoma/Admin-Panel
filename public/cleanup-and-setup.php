<?php
echo "Starting cleanup and setup...\n";

// Clear Laravel log file
$logFile = dirname(__DIR__) . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    file_put_contents($logFile, '');
    echo "Laravel log file cleared.\n";
}

// Clear compiled views
$viewsPath = dirname(__DIR__) . '/storage/framework/views';
if (is_dir($viewsPath)) {
    $files = glob($viewsPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "Compiled views cleared.\n";
}

// Clear cache files
$cachePath = dirname(__DIR__) . '/storage/framework/cache';
if (is_dir($cachePath)) {
    $files = glob($cachePath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "Cache files cleared.\n";
}

// Set permissions for storage directories
$storagePaths = [
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

foreach ($storagePaths as $path) {
    $fullPath = dirname(__DIR__) . '/' . $path;
    if (is_dir($fullPath)) {
        chmod($fullPath, 0775);
        echo "Set permissions for $path.\n";
    }
}

echo "Cleanup and setup completed!\n";
echo "Next steps:\n";
echo "1. Visit /simple-setup.php to initialize the database\n";
echo "2. Delete cleanup-and-setup.php and simple-setup.php for security\n";
echo "3. Rename .htaccess.bak files back to .htaccess\n";
?>
