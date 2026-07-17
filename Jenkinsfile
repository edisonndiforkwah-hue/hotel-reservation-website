pipeline {
    agent any

    environment {
        APP_ENV = 'testing'
        APP_DEBUG = 'false'
        DB_CONNECTION = 'mysql'
        DB_HOST = "${env.DB_HOST ?: '127.0.0.1'}"
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
                    php -v
                    composer --version
                    node -v
                    npm -v
                '''
            }
        }

        stage('Prepare MySQL') {
            steps {
                sh '''
                    if command -v docker >/dev/null 2>&1; then
                        docker rm -f hotel-project-mysql >/dev/null 2>&1 || true
                        docker run -d --name hotel-project-mysql \
                            -e MYSQL_ROOT_PASSWORD=${DB_PASSWORD} \
                            -e MYSQL_DATABASE=${DB_DATABASE} \
                            -p 3306:3306 \
                            mysql:8.4 --default-authentication-plugin=mysql_native_password

                        for i in $(seq 1 30); do
                            docker exec hotel-project-mysql mysqladmin ping -uroot -p${DB_PASSWORD} --silent && break
                            sleep 2
                        done
                    else
                        echo "Docker not found; expecting an existing MySQL instance at ${DB_HOST}:${DB_PORT}"
                    fi
                '''
            }
        }

        stage('Install PHP dependencies') {
            steps {
                sh '''
                    cp -n .env.example .env || true
                    php artisan key:generate --force || true
                    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
                    sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
                    sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
                    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
                    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
                    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
                    composer install --prefer-dist --no-progress
                    php artisan migrate --force
                '''
            }
        }

        stage('Install frontend dependencies') {
            steps {
                sh '''
                    if [ -f package-lock.json ]; then
                        npm ci
                    else
                        npm install
                    fi
                    npm run build
                '''
            }
        }

        stage('Run tests') {
            steps {
                sh '''
                    php artisan config:clear
                    php artisan test --log-junit build/phpunit.junit.xml
                '''
            }
        }

        stage('Build Docker image') {
            when {
                branch 'main'
            }
            steps {
                sh '''
                    docker build -t hotel-project:${GIT_COMMIT::7} .
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
