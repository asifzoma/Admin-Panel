<?php

/**
 * Simple setup script for Admin Genie
 * Place this in your project root and access via browser after upload
 */

// Basic security - delete this file after successful setup
if (file_exists(__DIR__ . '/.env') && file_exists(__DIR__ . '/storage/.setup-complete')) {
    die('Setup already completed. Delete simple-setup.php for security.');
}

// Function to set directory permissions
function fixPermissions($dir, $dirMode = 0775, $fileMode = 0664) {
    if (!file_exists($dir)) {
        mkdir($dir, $dirMode, true);
    }
    chmod($dir, $dirMode);
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        chmod($item, $item->isDir() ? $dirMode : $fileMode);
    }
}

// Function to create storage link without artisan
function createStorageLink() {
    $target = __DIR__ . '/storage/app/public';
    $link = __DIR__ . '/public/storage';
    
    if (!file_exists($target)) {
        mkdir($target, 0775, true);
    }
    
    if (file_exists($link)) {
        unlink($link);
    }
    
    // Try symlink first
    if (!@symlink($target, $link)) {
        // If symlink fails, create directory and copy contents
        if (!file_exists($link)) {
            mkdir($link, 0775, true);
        }
        
        // Copy any existing files from storage/app/public to public/storage
        if (is_dir($target)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $item) {
                $dest = $link . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                if ($item->isDir()) {
                    if (!file_exists($dest)) {
                        mkdir($dest, 0775, true);
                    }
                } else {
                    copy($item, $dest);
                }
            }
        }
    }
}

$errors = [];
$success = [];

// 1. Check PHP version and extensions
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    $success[] = "PHP version " . PHP_VERSION . " OK";
} else {
    $errors[] = "PHP version must be >= 8.1.0. Current: " . PHP_VERSION;
}

$required_extensions = ['pdo_sqlite', 'sqlite3', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "Extension {$ext} OK";
    } else {
        $errors[] = "Required extension {$ext} is missing";
    }
}

// 2. Fix directory permissions
$directories = [
    __DIR__ . '/storage',
    __DIR__ . '/storage/app',
    __DIR__ . '/storage/app/public',
    __DIR__ . '/storage/framework',
    __DIR__ . '/storage/framework/cache',
    __DIR__ . '/storage/framework/sessions',
    __DIR__ . '/storage/framework/views',
    __DIR__ . '/storage/logs',
    __DIR__ . '/bootstrap/cache',
    __DIR__ . '/database'
];

foreach ($directories as $dir) {
    try {
        fixPermissions($dir);
        $success[] = "Fixed permissions for {$dir}";
    } catch (Exception $e) {
        $errors[] = "Failed to fix permissions for {$dir}: " . $e->getMessage();
    }
}

// 3. Ensure database exists and is writable
$dbFile = __DIR__ . '/database/database.sqlite';
if (!file_exists($dbFile)) {
    if (touch($dbFile)) {
        chmod($dbFile, 0664);
        $success[] = "Created database file";

        // Initialize database with schema and admin user
        try {
            $pdo = new PDO('sqlite:' . $dbFile);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Read and execute SQL setup file
            $sql = file_get_contents(__DIR__ . '/database-setup.sql');
            $pdo->exec($sql);
            
            $success[] = "Initialized database with schema and admin user";
        } catch (PDOException $e) {
            $errors[] = "Failed to initialize database: " . $e->getMessage();
        }
    } else {
        $errors[] = "Failed to create database file";
    }
} else {
    if (is_writable($dbFile)) {
        $success[] = "Database file exists and is writable";
    } else {
        chmod($dbFile, 0664);
        $success[] = "Fixed database file permissions";
    }
}

// 4. Create storage link
try {
    createStorageLink();
    $success[] = "Created storage link";
} catch (Exception $e) {
    $errors[] = "Failed to create storage link: " . $e->getMessage();
}

// 5. Clear cached files
$cacheFiles = glob(__DIR__ . '/bootstrap/cache/*.php');
foreach ($cacheFiles as $file) {
    @unlink($file);
}
$success[] = "Cleared cache files";

// 6. Mark setup as complete if no errors
if (empty($errors)) {
    file_put_contents(__DIR__ . '/storage/.setup-complete', date('Y-m-d H:i:s'));
    $success[] = "Setup completed successfully!";
}

// Output results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Genie Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Admin Genie Setup</h2>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <h5>Errors Found:</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <h5>Setup Steps Completed:</h5>
                                <ul class="mb-0">
                                    <?php foreach ($success as $msg): ?>
                                        <li><?php echo htmlspecialchars($msg); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (empty($errors)): ?>
                            <div class="alert alert-info">
                                <strong>Next Steps:</strong>
                                <ol class="mb-0">
                                    <li>Delete this file (simple-setup.php)</li>
                                    <li>Visit your site and log in with:<br>
                                        Email: admin@admin.com<br>
                                        Password: password
                                    </li>
                                    <li>Change the admin password immediately!</li>
                                </ol>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                Please fix the errors above and refresh this page.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>