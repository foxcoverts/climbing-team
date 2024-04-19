#!/bin/bash
set -e

echo "Deployment started ..."

# Fix permissions
find $(pwd) -type f -not -path "$(pwd)/logs/*" -exec chmod 664 {} \;
find $(pwd) -type d -not -name "logs" -exec chmod 775 {} \;
chmod -R o+w storage bootstrap/cache

# Clear the old cache
php8.2-cli artisan optimize:clear

# Run database migrations
php8.2-cli artisan migrate --force -n

# Setup storage links
php8.2-cli artisan storage:link

# Recreate cache
php8.2-cli artisan optimize

# Exit maintenance mode
php8.2-cli artisan up

echo "Deployment finished!"
