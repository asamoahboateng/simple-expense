#!/usr/bin/env bash
set -e

echo "Running init tasks..."

# Ensure storage directories exist before anything boots the framework
# (composer's package:discover boots Laravel, and view.compiled resolves via
# realpath() which returns false - not an error - when the dir is missing)
# Written without brace expansion: this entrypoint runs under /bin/sh (ash),
# which doesn't support {a,b,c} expansion like bash does.
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
  storage/app/public storage/logs bootstrap/cache

# Install/update Composer dependencies
echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-interaction --no-cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Link storage
echo "Linking storage..."
php artisan storage:link 2>/dev/null || true

# Install npm dependencies and build assets
echo "Installing npm dependencies..."
npm install

echo "Building assets..."
npm run build

echo "Init tasks completed successfully."
