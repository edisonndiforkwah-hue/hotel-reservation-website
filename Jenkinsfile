pipeline {
    agent any

    environment {
        APP_ENV = 'testing'
        APP_DEBUG = 'false'
        DB_CONNECTION = 'mysql'
        DB_HOST = "${env.DB_HOST ?: 'hotel-project-mysql'}"
        DB_PORT = "${env.DB_PORT ?: '3306'}"
        DB_DATABASE = "${env.DB_DATABASE ?: 'hotel_project_testing'}"
        DB_USERNAME = "${env.DB_USERNAME ?: 'root'}"
        DB_PASSWORD = "${env.DB_PASSWORD ?: 'root'}"
        COMPOSER_NO_INTERACTION = '1'
        CI = 'true'
    }

    options {
        timeout(time: 45, unit: 'MINUTES')
        timestamps()
        buildDiscarder(logRotator(numToKeepStr: '10'))
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Building ${env.GIT_BRANCH ?: 'unknown'} @ ${env.GIT_COMMIT ?: 'unknown'}"
            }
        }

        stage('Prepare environment') {
            steps {
                sh '''
                    docker --version
                    docker info >/dev/null
                '''
            }
        }

        stage('Prepare MySQL') {
            steps {
                sh '''
                    docker network inspect hotel-net >/dev/null 2>&1 || docker network create hotel-net
                    docker rm -f hotel-project-mysql >/dev/null 2>&1 || true
                    docker run -d --name hotel-project-mysql --network hotel-net \
                        -e MYSQL_ROOT_PASSWORD=${DB_PASSWORD} \
                        -e MYSQL_DATABASE=${DB_DATABASE} \
                        -p 3306:3306 \
                        mysql:8.4 --default-authentication-plugin=mysql_native_password

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
        }

        stage('Install PHP dependencies') {
            steps {
                sh '''
                    set -e
                    BUILD_DIR="/tmp/hotel-website-build-${BUILD_TAG:-local}"
                    rm -rf "$BUILD_DIR"
                    mkdir -p "$BUILD_DIR"
                    tar -C "$PWD" -cf - . | tar -C "$BUILD_DIR" -xf -
                    cd "$BUILD_DIR"
                    cp -n .env.example .env || true
                    docker run --rm -v "$BUILD_DIR":/app -w /app --network hotel-net \
                        -e APP_ENV=${APP_ENV} -e APP_DEBUG=${APP_DEBUG} \
                        -e DB_CONNECTION=${DB_CONNECTION} -e DB_HOST=${DB_HOST} -e DB_PORT=${DB_PORT} \
                        -e DB_DATABASE=${DB_DATABASE} -e DB_USERNAME=${DB_USERNAME} -e DB_PASSWORD=${DB_PASSWORD} \
                        composer:2 sh -c "composer install --prefer-dist --no-progress --no-interaction; php artisan key:generate --force || true; sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION}|' .env; sed -i 's|^DB_HOST=.*|DB_HOST=${DB_HOST}|' .env; sed -i 's|^DB_PORT=.*|DB_PORT=${DB_PORT}|' .env; sed -i 's|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|' .env; sed -i 's|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|' .env; sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|' .env; php artisan migrate --force"
                '''
            }
        }

        stage('Install frontend dependencies') {
            steps {
                sh '''
                    BUILD_DIR="/tmp/hotel-website-build-${BUILD_TAG:-local}"
                    rm -rf "$BUILD_DIR"
                    mkdir -p "$BUILD_DIR"
                    tar -C "$PWD" -cf - . | tar -C "$BUILD_DIR" -xf -
                    docker run --rm -v "$BUILD_DIR":/app -w /app node:22-alpine sh -c "if [ -f package-lock.json ]; then npm ci --no-audit --no-fund; else npm install --no-audit --no-fund; fi; npm run build"
                '''
            }
        }

        stage('Run tests') {
            steps {
                sh '''
                    set -e
                    BUILD_DIR="/tmp/hotel-website-build-${BUILD_TAG:-local}"
                    rm -rf "$BUILD_DIR"
                    mkdir -p "$BUILD_DIR"
                    tar -C "$PWD" -cf - . | tar -C "$BUILD_DIR" -xf -
                    mkdir -p "$BUILD_DIR/build"
                    docker run --rm -v "$BUILD_DIR":/app -w /app --network hotel-net \
                        -e APP_ENV=${APP_ENV} -e APP_DEBUG=${APP_DEBUG} \
                        -e DB_CONNECTION=${DB_CONNECTION} -e DB_HOST=${DB_HOST} -e DB_PORT=${DB_PORT} \
                        -e DB_DATABASE=${DB_DATABASE} -e DB_USERNAME=${DB_USERNAME} -e DB_PASSWORD=${DB_PASSWORD} \
                        composer:2 sh -c "php artisan config:clear; php artisan test --log-junit build/phpunit.junit.xml"
                '''
            }
        }

        stage('Build Docker image') {
            when {
                branch 'main'
            }
            steps {
                sh '''
                    BUILD_DIR="/tmp/hotel-website-build-${BUILD_TAG:-local}"
                    rm -rf "$BUILD_DIR"
                    mkdir -p "$BUILD_DIR"
                    tar -C "$PWD" -cf - . | tar -C "$BUILD_DIR" -xf -
                    docker build -t hotel-project:${GIT_COMMIT::7} "$BUILD_DIR"
                '''
            }
        }
    }

    post {
        always {
            archiveArtifacts artifacts: 'public/build/**, storage/logs/*.log', allowEmptyArchive: true
            junit allowEmptyResults: true, testResults: 'build/phpunit.junit.xml'
        }
        success {
            echo 'Pipeline completed successfully.'
        }
        failure {
            echo 'Pipeline failed. Check the build logs for details.'
        }
    }
}
