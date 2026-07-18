pipeline {
    agent any

    stages {
        stage('Pull Code') {
            steps {
                echo 'Pulling latest code...'
                // Git checkout happens automatically, but we can print a message
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building the hotel website Docker image...'
                sh 'docker build -t hotel-website .'
            }
        }

        stage('Deploy Container') {
            steps {
                echo 'Stopping old container and deploying new one...'
                // Stop the old container if it exists, ignore errors if it doesn't
                sh 'docker stop hotel-app || true'
                sh 'docker rm hotel-app || true'
                // Run the new container on port 8888
                sh 'docker run -d --name hotel-app -p 8888:80 hotel-website'
            }
        }

        stage('Test Website') {
            steps {
                echo 'Running HTTP Smoke Tests...'
                // Give the container 3 seconds to fully boot up
                sh 'sleep 3'
                // Curl the website and check if it returns HTTP status code 200
                sh 'curl -s -o /dev/null -w "%{http_code}" http://localhost:8888 | grep 200'
                echo 'Smoke test passed! Website is healthy.'
            }
        }
    }
}
