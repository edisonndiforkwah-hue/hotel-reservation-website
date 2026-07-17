pipeline {

    agent any

    environment {
        APP_NAME = "hotel-website"
        DOCKER_IMAGE = "hotel-app:latest"
        COMPOSE_FILE = "docker-compose.yml"
    }

    stages {

        stage('Checkout Code') {
            steps {
                echo "Pulling latest code from GitHub..."
                checkout scm
            }
        }


        stage('Install Dependencies') {
            steps {
                echo "Installing Laravel dependencies..."

                sh '''
                docker run --rm \
                -v $(pwd):/var/www/html \
                -w /var/www/html \
                composer:latest \
                composer install \
                --no-interaction \
                --prefer-dist \
                --optimize-autoloader
                '''
            }
        }


        stage('Build Docker Image') {
            steps {
                echo "Building Laravel Docker image..."

                sh '''
                docker compose build
                '''
            }
        }


        stage('Stop Existing Containers') {
            steps {
                echo "Stopping old containers..."

                sh '''
                docker compose down
                '''
            }
        }


        stage('Start Application') {
            steps {
                echo "Starting Laravel application..."

                sh '''
                docker compose up -d
                '''
            }
        }


        stage('Laravel Setup') {
            steps {

                echo "Running Laravel commands..."

                sh '''
                sleep 20

                docker exec hotel_app php artisan key:generate --force || true

                docker exec hotel_app php artisan migrate --force

                docker exec hotel_app php artisan storage:link || true

                docker exec hotel_app php artisan config:cache

                docker exec hotel_app php artisan route:cache

                docker exec hotel_app php artisan view:cache
                '''
            }
        }


        stage('Health Check') {
            steps {

                echo "Checking containers..."

                sh '''
                docker compose ps
                '''
            }
        }

    }


    post {

        success {
            echo "Deployment successful 🚀"
        }

        failure {
            echo "Deployment failed ❌"

            sh '''
            docker compose logs --tail=100
            '''
        }

    }
}