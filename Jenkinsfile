Jenkins file : 
pipeline {
    agent any

    stages {
        stage('Checkout Code') {
            steps {
                checkout scm
            }
        }
        stage('Build') {
            steps {
                sh 'echo "Building the project..."'
                sh 'docker build -t natvannak/usea-php-app:${BUILD_NUMBER} .'
               
            }
        }
        stage('Push Image') {
            steps {
                sh 'echo "Push image to registry..."'
                withCredentials([usernamePassword(credentialsId: 'docker-hub-id', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                    sh 'echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin'
                    sh 'docker push krolnoeurnrpisb/usea-php-app:${BUILD_NUMBER}'
                }
                // Add your test commands here
            }
        }
        stage('Deploy') {
            steps {
                script{
                    sh 'echo "Deploying the project..."'
                    // ssh '''
                    //     // remove container if it exists
                    //     ssh root@34.227.61.167 /var/projects/phpapp/updated_image_version.sh ${BUILD_NUMBER}
                    // '''
                    sh 'ssh root@3.239.208.125 /var/project/deploy.sh ${BUILD_NUMBER}'
                // Add your deploy commands here
                }
                
            }
        }
    }
}