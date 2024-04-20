#!/bin/bash
set -e

echo "Running post-deploy scripts"

# Ensure storage directories exist
mkdir -p storage/app/public storage/framework/{cache,sessions,testing,views} storage/logs

# Generate a new key
php8.2-cli artisan key:generate --force -n

# Clear the old cache
php8.2-cli artisan optimize:clear ||:

# Run database migrations
php8.2-cli artisan migrate --force -n

# Setup storage links
php8.2-cli artisan storage:link

# Recreate cache
php8.2-cli artisan optimize

# Exit maintenance mode
php8.2-cli artisan up

echo "Deployment finished!"
