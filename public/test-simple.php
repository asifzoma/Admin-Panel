<?php

require __DIR__.'/../vendor/autoload.php';

echo "Testing rescue function...\n";

if (function_exists('rescue')) {
    echo "✓ rescue function exists\n";
} else {
    echo "✗ rescue function does not exist\n";
}

if (function_exists('Illuminate\Foundation\Exceptions\rescue')) {
    echo "✓ Illuminate\Foundation\Exceptions\rescue function exists\n";
} else {
    echo "✗ Illuminate\Foundation\Exceptions\rescue function does not exist\n";
}

echo "Testing with function...\n";
if (function_exists('with')) {
    echo "✓ with function exists\n";
} else {
    echo "✗ with function does not exist\n";
}
