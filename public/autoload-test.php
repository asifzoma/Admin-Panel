<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo "<h1>Autoloader Test</h1>";

try {
    // Test autoloading App namespace
    if (class_exists('App\Models\User')) {
        echo "<p>✓ Can load App\\Models\\User class</p>";
    }
    
    // Test autoloading Illuminate namespace
    if (class_exists('Illuminate\Support\Str')) {
        echo "<p>✓ Can load Illuminate\\Support\\Str class</p>";
    }
    
    // Test database connection
    $dbPath = dirname(__DIR__) . '/database/database.sqlite';
    if (file_exists($dbPath)) {
        echo "<p>✓ Database file exists at: " . $dbPath . "</p>";
        
        try {
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<p>✓ Can connect to database</p>";
            
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
            echo "<p>✓ Found tables: " . implode(', ', $tables) . "</p>";
        } catch (PDOException $e) {
            echo "<p>⚠ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>⚠ Database file not found</p>";
    }
    
    // Test storage permissions
    $storagePath = dirname(__DIR__) . '/storage';
    if (is_writable($storagePath)) {
        echo "<p>✓ Storage directory is writable</p>";
    } else {
        echo "<p>⚠ Storage directory is not writable</p>";
    }
    
    // Test cache permissions
    $cachePath = dirname(__DIR__) . '/bootstrap/cache';
    if (is_writable($cachePath)) {
        echo "<p>✓ Cache directory is writable</p>";
    } else {
        echo "<p>⚠ Cache directory is not writable</p>";
    }
    
    echo "<h2>Next Steps:</h2>";
    echo "<ol>";
    echo "<li><a href='cleanup-and-setup.php'>Run cleanup and setup</a></li>";
    echo "<li><a href='database-migration.php'>Run database migration</a></li>";
    echo "<li><a href='fix-employees-table.php'>Fix employees table</a></li>";
    echo "<li><a href='final-setup.php'>Run final setup</a></li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p>⚠ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>File: " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}
