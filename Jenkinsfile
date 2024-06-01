pipeline {
    agent any
    
    environment {
        NGINX_WEB_DIR = '/var/www/html/backend'
        GITHUB_REPO = 'https://github.com/your_username/your_repo.git'
        DISCORD_WEBHOOK_URL = 'https://discord.com/api/webhooks/your_webhook_url'
        
        APP_NAME = 'hyfish-api'
        APP_ENV = 'development'
        DB_CONNECTION = 'mysql'
        DB_HOST = credentials('DB_HOST')
        DB_PORT = '3306'
        DB_DATABASE = credentials('DB_DATABASE')
        DB_USERNAME = credentials('DB_USERNAME')
        DB_PASSWORD = credentials('DB_PASSWORD')
    }
    
    stages {
        stage('Remove Existing Project') {
            steps {
                sh "rm -rf ${NGINX_WEB_DIR}"
            }
        }
        
        stage('Clone Repository') {
            steps {
                git branch: 'master', url: "${GITHUB_REPO}"
            }
        }
        
        stage('Install Dependencies') {
            steps {
                sh "cd backend && composer install --no-dev --optimize-autoloader"
            }
        }
        
        stage('Build') {
            steps {
                sh "php artisan optimize"
            }
        }
        
        stage('Create .env File') {
            steps {
                script {
                    sh """
                    sed -i 's/APP_NAME=Laravel/APP_NAME=${APP_NAME}/' .env
                    sed -i 's/APP_ENV=local/APP_ENV=${APP_ENV}/' .env
                    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=${DB_CONNECTION}/' .env
                    sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=${DB_HOST}/' .env
                    sed -i 's/# DB_PORT=3306/DB_PORT=${DB_PORT}/' .env
                    sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=${DB_DATABASE}/' .env
                    sed -i 's/# DB_USERNAME=root/DB_USERNAME=${DB_USERNAME}/' .env
                    sed -i 's/# DB_PASSWORD=/DB_PASSWORD=${DB_PASSWORD}/' .env
                    """
                }
            }
        }
        
        stage('Deploy') {
            steps {
                sh "sudo cp -r * ${NGINX_WEB_DIR}"
            }
        }
        
        stage('Reload Nginx') {
            steps {
                sh "systemctl reload nginx"
            }
        }
        
        stage('Notify Discord') {
            steps {
                script {
                    def payload = "{\"content\": \"Deployment of Laravel backend API service completed successfully.\"}"
                    sh "curl -X POST -H 'Content-type: application/json' --data '${payload}' ${DISCORD_WEBHOOK_URL}"
                }
            }
        }
    }
    
    post {
        failure {
            script {
                def payload = "{\"content\": \"Deployment of Laravel backend API service failed!\"}"
                sh "curl -X POST -H 'Content-type: application/json' --data '${payload}' ${DISCORD_WEBHOOK_URL}"
            }
        }
    }
}
