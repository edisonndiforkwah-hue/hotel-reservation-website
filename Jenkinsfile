pipeline {
    agent any

    stages {
        stage('Build Image') {
            steps {
                echo 'Building Docker image...'
                sh 'docker build -t hotel-website .'
            }
        }

        stage('Deploy Container') {
            steps {
                echo 'Stopping old container and deploying new one...'
                // The || true ensures the script doesn't crash if the container isn't already running
                sh 'docker stop hotel-app || true'
                sh 'docker rm hotel-app || true'
                sh 'docker run -d --name hotel-app -p 8888:80 hotel-website'
            }
        }

        stage('Test Website') {
            steps {
                echo 'Running HTTP Smoke Tests...'
                sleep 5
                sh 'curl -I http://localhost:8888 | grep "200 OK"'
            }
        }
    }
}pipeline {
    agent any

    stages {
        stage('Build Image') {
            steps {
                echo 'Building Docker image...'
                sh 'docker build -t hotel-website .'
            }
        }

        stage('Deploy Container') {
            steps {
                echo 'Stopping old container and deploying new one...'
                // The || true ensures the script doesn't crash if the container isn't already running
                sh 'docker stop hotel-app || true'
                sh 'docker rm hotel-app || true'
                sh 'docker run -d --name hotel-app -p 8888:80 hotel-website'
            }
        }

        stage('Test Website') {
            steps {
                echo 'Running HTTP Smoke Tests...'
                sleep 5
                sh 'curl -I http://localhost:8888 | grep "200 OK"'
            }
        }
    }
}
