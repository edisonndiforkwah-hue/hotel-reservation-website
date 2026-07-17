# Hotel Reservation Website

This project is a Laravel-based hotel reservation website.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- Docker (optional, for containerized runs)
- MySQL

## Local development

1. Copy the environment example:
   ```bash
   copy .env.example .env
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies and build assets:
   ```bash
   npm install
   npm run build
   ```
4. Run migrations:
   ```bash
   php artisan migrate
   ```
5. Start the app:
   ```bash
   php artisan serve
   ```

## Docker

You can run the app with Docker Compose using MySQL:

```bash
copy .env.docker.example .env
docker compose up --build
```

This will start:
- the Laravel app container
- a MySQL container
- a Caddy reverse proxy

## Jenkins CI

A Jenkins pipeline is included in [Jenkinsfile](Jenkinsfile). It installs dependencies, runs tests, and builds the Docker image on the main branch.
