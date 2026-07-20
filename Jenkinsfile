pipeline {
    agent any

    stages {
        stage('Build Image') {
            steps {
                echo 'Building production-grade Laravel Docker image...'
                sh 'docker build -t hotel-website .'
            }
        }

        stage('Deploy Container') {
            steps {
                echo 'Clearing old runtime environments...'
                sh 'docker stop hotel-app || true'
                sh 'docker rm hotel-app || true'
                
                echo 'Launching containerized Laravel Application on port 8888...'
                sh 'docker run -d --name hotel-app -p 8888:80 hotel-website'
            }
        }

        stage('App Smoke Test') {
            steps {
                echo 'Waiting for framework bootstrapper and Nginx proxy...'
                sleep 5
                
                echo 'Pinging Laravel core health check endpoint...'
                sh 'curl -f http://localhost:8888/up'
            }
        }
    }
}
