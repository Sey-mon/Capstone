<?php

/**
 * Simple log monitoring script for Windows
 * Usage: php artisan-logs.php
 */

$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "Log file not found: $logFile\n";
    exit(1);
}

echo "Monitoring Laravel logs (Press Ctrl+C to stop)...\n";
echo "Log file: $logFile\n";
echo str_repeat('-', 50) . "\n";

$lastSize = filesize($logFile);
$lastModified = filemtime($logFile);

while (true) {
    clearstatcache();
    
    if (file_exists($logFile)) {
        $currentSize = filesize($logFile);
        $currentModified = filemtime($logFile);
        
        if ($currentModified > $lastModified || $currentSize > $lastSize) {
            $handle = fopen($logFile, 'r');
            fseek($handle, $lastSize);
            
            while (($line = fgets($handle)) !== false) {
                echo trim($line) . "\n";
            }
            
            fclose($handle);
            $lastSize = $currentSize;
            $lastModified = $currentModified;
        }
    }
    
    sleep(1);
} 