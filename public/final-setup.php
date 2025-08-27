<?php
/**
 * Final Setup Script for Admin Panel
 * This script addresses all the issues found in the cPanel logs
 */

echo "<h1>Admin Panel Final Setup</h1>\n";
echo "<p>Starting comprehensive setup...</p>\n";

// 1. Clear all caches and logs
echo "<h2>Step 1: Clearing Caches and Logs</h2>\n";

$pathsToClear = [
    'storage/logs/laravel.log',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($pathsToClear as $path) {
    $fullPath = dirname(__DIR__) . '/' . $path;
    if (file_exists($fullPath)) {
        if (is_file($fullPath)) {
            unlink($fullPath);
            echo "<p>✓ Cleared: $path</p>\n";
        } elseif (is_dir($fullPath)) {
            $files = glob($fullPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            echo "<p>✓ Cleared directory: $path</p>\n";
        }
    }
}

// 2. Set proper permissions
echo "<h2>Step 2: Setting Permissions</h2>\n";

$pathsToSetPermissions = [
    'storage' => 0775,
    'storage/app' => 0775,
    'storage/app/public' => 0775,
    'storage/framework' => 0775,
    'storage/framework/cache' => 0775,
    'storage/framework/sessions' => 0775,
    'storage/framework/views' => 0775,
    'storage/logs' => 0775,
    'bootstrap/cache' => 0775,
    'database' => 0775
];

foreach ($pathsToSetPermissions as $path => $permission) {
    $fullPath = dirname(__DIR__) . '/' . $path;
    if (file_exists($fullPath)) {
        chmod($fullPath, $permission);
        echo "<p>✓ Set permissions on: $path</p>\n";
    }
}

// 3. Create storage link
echo "<h2>Step 3: Creating Storage Link</h2>\n";

$publicStoragePath = dirname(__DIR__) . '/public/storage';
$storagePath = dirname(__DIR__) . '/storage/app/public';

// Remove existing symlink if it exists
if (is_link($publicStoragePath)) {
    unlink($publicStoragePath);
    echo "<p>✓ Removed existing storage symlink</p>\n";
}

// Create new symlink
if (symlink($storagePath, $publicStoragePath)) {
    echo "<p>✓ Created storage symlink</p>\n";
} else {
    echo "<p>⚠ Could not create storage symlink (this is normal on some hosts)</p>\n";
}

// 4. Initialize SQLite database
echo "<h2>Step 4: Initializing Database</h2>\n";

$dbPath = dirname(__DIR__) . '/database/database.sqlite';

// Create database file if it doesn't exist
if (!file_exists($dbPath)) {
    touch($dbPath);
    chmod($dbPath, 0664);
    echo "<p>✓ Created database file</p>\n";
}

// Test database connection
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>✓ Database connection successful</p>\n";
    echo "<p>✓ Found " . count($tables) . " tables: " . implode(', ', $tables) . "</p>\n";
    
} catch (Exception $e) {
    echo "<p>⚠ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

// 5. Create .env file if it doesn't exist
echo "<h2>Step 5: Environment Configuration</h2>\n";

$envPath = dirname(__DIR__) . '/.env';
if (!file_exists($envPath)) {
    $envContent = "APP_NAME=\"Admin Panel\"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://laravel.asif-ahmad.netmatters-scs.co.uk

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=laravel.asif-ahmad.netmatters-scs.co.uk
SESSION_SECURE_COOKIE=true

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME=\"\${APP_NAME}\"
VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"";
    
    file_put_contents($envPath, $envContent);
    echo "<p>✓ Created .env file</p>\n";
} else {
    echo "<p>✓ .env file already exists</p>\n";
}

// 6. Generate application key
echo "<h2>Step 6: Generating Application Key</h2>\n";

$envContent = file_get_contents($envPath);
if (strpos($envContent, 'APP_KEY=base64:your-app-key-here') !== false) {
    $key = base64_encode(random_bytes(32));
    $envContent = str_replace('APP_KEY=base64:your-app-key-here', 'APP_KEY=base64:' . $key, $envContent);
    file_put_contents($envPath, $envContent);
    echo "<p>✓ Generated new application key</p>\n";
} else {
    echo "<p>✓ Application key already exists</p>\n";
}

// 7. Create necessary directories
echo "<h2>Step 7: Creating Required Directories</h2>\n";

$directories = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($directories as $dir) {
    $fullPath = dirname(__DIR__) . '/' . $dir;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0775, true);
        echo "<p>✓ Created directory: $dir</p>\n";
    }
}

echo "<h2>Setup Complete!</h2>\n";
echo "<p><strong>Next steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Delete this setup file for security</li>\n";
echo "<li>Rename .htaccess.bak files back to .htaccess</li>\n";
echo "<li>Test your application at: <a href='/'>Home Page</a></li>\n";
echo "<li>Login with: admin@admin.com / password</li>\n";
echo "</ol>\n";

echo "<p><strong>If you still get errors:</strong></p>\n";
echo "<ul>\n";
echo "<li>Check that all Laravel core files are present</li>\n";
echo "<li>Ensure vendor directory contains all dependencies</li>\n";
echo "<li>Run 'composer install' on the server if possible</li>\n";
echo "<li>Check server error logs for specific issues</li>\n";
echo "</ul>\n";
?>
