# Windows Setup Guide

## Issue Resolution: pcntl Extension

The Laravel Pail package requires the `pcntl` extension which is not available on Windows. This has been resolved by providing Windows-compatible alternatives.

## Changes Made

### 1. Modified `composer.json` Scripts

- **Removed Pail from the main `dev` script** - This prevents the pcntl error on Windows
- **Added `dev:windows` script** - A Windows-compatible development script
- **Added `logs` script** - A custom log monitoring solution

### 2. Created Custom Log Monitor

- **`artisan-logs.php`** - A simple PHP script that monitors Laravel logs without requiring pcntl

## Usage

### For Windows Users:

```bash
# Use the Windows-compatible development script
composer run dev:windows

# Or run individual commands:
composer run logs          # Monitor logs only
php artisan serve          # Start development server
php artisan queue:listen   # Start queue listener
npm run dev               # Start Vite development server
```

### For Unix/Linux Users:

```bash
# Use the original development script (includes Pail)
composer run dev
```

## Log Monitoring

### Windows:
```bash
composer run logs
# or
php artisan-logs.php
```

### Unix/Linux:
```bash
php artisan pail --timeout=0
```

## Alternative Solutions

If you need more advanced log monitoring on Windows, consider:

1. **Using WSL (Windows Subsystem for Linux)** - Install Ubuntu and run Laravel in WSL
2. **Using Docker** - Run Laravel in a Docker container with Linux
3. **Using Git Bash** - Some users report success with Git Bash on Windows

## Troubleshooting

If you encounter any issues:

1. Make sure you're using the correct script for your OS
2. Check that the log file exists at `storage/logs/laravel.log`
3. Ensure you have the necessary permissions to read the log file
4. For advanced features, consider using WSL or Docker 