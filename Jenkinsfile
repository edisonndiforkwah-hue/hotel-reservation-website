node {
    properties([
        buildDiscarder(logRotator(numToKeepStr: '10'))
    ])

    def appEnv = 'testing'
    def appDebug = 'false'
    def dbConnection = 'mysql'
    def dbHost = env.DB_HOST ?: 'hotel-project-mysql'
    def dbPort = env.DB_PORT ?: '3306'
    def dbDatabase = env.DB_DATABASE ?: 'hotel_project_testing'
    def dbUsername = env.DB_USERNAME ?: 'root'
    def dbPassword = env.DB_PASSWORD ?: 'root'

    withEnv([
        "APP_ENV=${appEnv}",
        "APP_DEBUG=${appDebug}",
        "DB_CONNECTION=${dbConnection}",
        "DB_HOST=${dbHost}",
        "DB_PORT=${dbPort}",
        "DB_DATABASE=${dbDatabase}",
        "DB_USERNAME=${dbUsername}",
        "DB_PASSWORD=${dbPassword}",
        'COMPOSER_NO_INTERACTION=1',
        'CI=true'
    ]) {
        timeout(time: 45, unit: 'MINUTES') {
            timestamps {
                try {
                    stage('Checkout') {
                        echo "Building ${env.GIT_BRANCH ?: 'unknown'} @ ${env.GIT_COMMIT ?: 'unknown'}"
                    }

                    stage('Prepare environment') {
                        sh '''
                            docker --version
                            docker info >/dev/null
                        '''
                    }

                    stage('Prepare MySQL') {
                        sh '''
                            docker network inspect hotel-net >/dev/null 2>&1 || docker network create hotel-net
                            docker rm -f hotel-project-mysql >/dev/null 2>&1 || true
                            docker run -d --name hotel-project-mysql --network hotel-net \
                                -e MYSQL_ROOT_PASSWORD=${DB_PASSWORD} \
                                -e MYSQL_DATABASE=${DB_DATABASE} \
                                -p 3306:3306 \
                                mysql:8.4

                            for i in $(seq 1 60); do
                                if docker inspect -f '{{.State.Running}}' hotel-project-mysql 2>/dev/null | grep -q true; then
                                    if docker exec hotel-project-mysql mysqladmin ping -uroot -p${DB_PASSWORD} --silent >/dev/null 2>&1; then
                                        break
                                    fi
                                else
                                    echo "MySQL container exited unexpectedly. Logs:" >&2
                                    docker logs hotel-project-mysql 2>&1 || true
                                    exit 1
                                fi
                                sleep 2
                            done
                        '''
                    }

                    stage('Clean workspace and Docker state') {
                        sh '''
                            set -e
                            docker system prune -af --volumes >/dev/null 2>&1 || true
                            rm -rf "$WORKSPACE/vendor" "$WORKSPACE/node_modules" "$WORKSPACE/public/build" "$WORKSPACE/storage/framework" "$WORKSPACE/storage/logs" "$WORKSPACE/bootstrap/cache" 2>/dev/null || true
                            mkdir -p "$WORKSPACE/storage/logs" "$WORKSPACE/bootstrap/cache"
                        '''
                    }

                    stage('Install PHP dependencies') {
                        sh '''
                            set -e
                            cd "$WORKSPACE"
                            cp -n .env.example .env || true
                            docker run --rm -v "$WORKSPACE":/app -w /app --network hotel-net \
                                -e APP_ENV=${APP_ENV} -e APP_DEBUG=${APP_DEBUG} \
                                -e DB_CONNECTION=${DB_CONNECTION} -e DB_HOST=${DB_HOST} -e DB_PORT=${DB_PORT} \
                                -e DB_DATABASE=${DB_DATABASE} -e DB_USERNAME=${DB_USERNAME} -e DB_PASSWORD=${DB_PASSWORD} \
                                php:8.2-cli-bookworm sh -c "apt-get update && apt-get install -y --no-install-recommends curl git unzip libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libxml2-dev default-mysql-client && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip opcache && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && git config --global --add safe.directory /app && composer install --prefer-dist --no-progress --no-interaction && php artisan key:generate --force || true && sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION}|' .env && sed -i 's|^DB_HOST=.*|DB_HOST=${DB_HOST}|' .env && sed -i 's|^DB_PORT=.*|DB_PORT=${DB_PORT}|' .env && sed -i 's|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|' .env && sed -i 's|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|' .env && sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|' .env && php artisan migrate --force"
                        '''
                    }

                    stage('Install frontend dependencies') {
                        sh '''
                            docker run --rm -v "$WORKSPACE":/app -w /app node:22-alpine sh -c "if [ -f package-lock.json ]; then npm ci --no-audit --no-fund; else npm install --no-audit --no-fund; fi; npm run build"
                        '''
                    }

                    stage('Run tests') {
                        sh '''
                            set -e
                            mkdir -p "$WORKSPACE/build"
                            docker run --rm -v "$WORKSPACE":/app -w /app --network hotel-net \
                                -e APP_ENV=${APP_ENV} -e APP_DEBUG=${APP_DEBUG} \
                                -e DB_CONNECTION=${DB_CONNECTION} -e DB_HOST=${DB_HOST} -e DB_PORT=${DB_PORT} \
                                -e DB_DATABASE=${DB_DATABASE} -e DB_USERNAME=${DB_USERNAME} -e DB_PASSWORD=${DB_PASSWORD} \
                                php:8.2-cli-bookworm sh -c "apt-get update && apt-get install -y --no-install-recommends curl git unzip libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libxml2-dev default-mysql-client && docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip opcache && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && git config --global --add safe.directory /app && php artisan config:clear && php artisan test --log-junit build/phpunit.junit.xml"
                        '''
                    }

                    stage('Build Docker image') {
                        sh '''
                            docker build -t hotel-project:latest "$WORKSPACE"
                        '''
                    }

                    stage('Deploy Container') {
                        sh '''
                            set -e
                            docker network inspect hotel-net >/dev/null 2>&1 || docker network create hotel-net
                            docker stop hotel-app >/dev/null 2>&1 || true
                            docker rm -f hotel-app >/dev/null 2>&1 || true

                            docker run -d --name hotel-app --network hotel-net -p 127.0.0.1:8888:80 \
                                -e APP_ENV=${APP_ENV} \
                                -e APP_DEBUG=${APP_DEBUG} \
                                -e DB_CONNECTION=${DB_CONNECTION} \
                                -e DB_HOST=${DB_HOST} \
                                -e DB_PORT=${DB_PORT} \
                                -e DB_DATABASE=${DB_DATABASE} \
                                -e DB_USERNAME=${DB_USERNAME} \
                                -e DB_PASSWORD=${DB_PASSWORD} \
                                hotel-project:latest

                            for i in $(seq 1 30); do
                                code=$(curl -s -o /tmp/hotel_app_response.html -w '%{http_code}' http://127.0.0.1:8888 || true)
                                if [ "$code" = "200" ] || [ "$code" = "302" ]; then
                                    echo "Application responded with HTTP $code"
                                    exit 0
                                fi
                                echo "Waiting for the app container to become reachable (attempt $i/30, last code=$code)..."
                                docker logs hotel-app 2>&1 || true
                                sleep 3
                            done

                            echo "Application did not become ready in time." >&2
                            docker logs hotel-app 2>&1 || true
                            exit 1
                        '''
                    }

                    stage('Test Website') {
                        sh '''
                            set -e
                            for i in $(seq 1 30); do
                                status=$(curl -s -o /dev/null -w '%{http_code}' http://127.0.0.1:8888 || true)
                                if [ "$status" = "200" ] || [ "$status" = "302" ]; then
                                    echo "Smoke test passed with HTTP $status"
                                    exit 0
                                fi
                                echo "Waiting for the website to respond (attempt $i/30, last code=$status)..."
                                docker logs hotel-app 2>&1 || true
                                sleep 3
                            done

                            echo "Smoke test failed. Last HTTP status: $status" >&2
                            docker logs hotel-app 2>&1 || true
                            exit 1
                        '''
                    }

                    echo 'Pipeline completed successfully.'
                } catch (err) {
                    echo 'Pipeline failed. Check the build logs for details.'
                    throw err
                } finally {
                    archiveArtifacts artifacts: 'public/build/**, storage/logs/*.log', allowEmptyArchive: true
                    junit allowEmptyResults: true, testResults: 'build/phpunit.junit.xml'
                }
            }
        }
    }
}
