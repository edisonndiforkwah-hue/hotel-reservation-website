#!/bin/sh
set -e

cd /var/www/html

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
php artisan migrate --force --no-interaction

if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
fi

exec "$@"
