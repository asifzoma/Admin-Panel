<?php

require __DIR__.'/../vendor/autoload.php';

try {
    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );
    echo "✓ Application class instantiated successfully\n";
    
    // Test Macroable trait
    if (in_array('Illuminate\Support\Traits\Macroable', class_uses_recursive('Illuminate\Foundation\Application'))) {
        echo "✓ Macroable trait is properly loaded\n";
    } else {
        echo "✗ Macroable trait is not loaded\n";
    }
    
    // Test container interface
    if ($app instanceof Illuminate\Contracts\Container\Container) {
        echo "✓ Application implements Container interface\n";
    } else {
        echo "✗ Application does not implement Container interface\n";
    }
    
    // Test service binding
    $app->bind('test-service', function() {
        return new stdClass;
    });
    $service = $app->make('test-service');
    echo "✓ Service container is working\n";
    
} catch (Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
