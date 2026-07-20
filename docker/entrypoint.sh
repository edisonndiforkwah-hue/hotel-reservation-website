#!/bin/sh
set -e

cd /var/www/html

# Create .env from .env.example if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Wait for MySQL to accept connections
if [ "$DB_CONNECTION" = "mysql" ] || [ "$DB_CONNECTION" = "mariadb" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
    until php -r "
        try {
            new PDO(
                'mysql:host=${DB_HOST};port=${DB_PORT}',
                '${DB_USERNAME}',
                '${DB_PASSWORD}',
                [PDO::ATTR_TIMEOUT => 3]
            );
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        sleep 2
    done
    echo "Database is ready."
fi

# Generate app key if missing
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    php artisan key:generate --force
fi

# Ensure writable directories
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
mkdir -p public/room_img public/gallery public/blog_img
chown -R www-data:www-data storage bootstrap/cache public/room_img public/gallery public/blog_img

php artisan storage:link --force 2>/dev/null || true

if ! php artisan migrate --force --no-interaction; then
    echo "Database migrations did not complete; continuing startup so the web server can still come up."
fi

if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    php artisan config:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
fi

exec "$@"
