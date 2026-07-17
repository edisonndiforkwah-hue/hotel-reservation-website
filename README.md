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

### Jenkins agent setup

For this pipeline to run successfully, the Jenkins agent needs:

- Docker installed and running
- Git installed
- access to the Docker daemon
- the following Jenkins plugins: Git, Pipeline, and Docker Pipeline (optional but recommended)

On a Linux agent, give the Jenkins user access to Docker with:

```bash
sudo usermod -aG docker jenkins
sudo systemctl restart docker
sudo systemctl restart jenkins
```

After that, verify the agent can run Docker commands:

```bash
sudo -u jenkins docker --version
sudo -u jenkins docker info
```
