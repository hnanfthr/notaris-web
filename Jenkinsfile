pipeline {
    agent any

    environment {
        // Hostinger SSH Configuration
        HOSTINGER_USER = 'u247903474'
        HOSTINGER_IP   = '145.79.25.206'
        HOSTINGER_PORT = '65002'
        
        // Direktori website di Hostinger (Sesuaikan dengan path di File Manager Hostinger Anda)
        // Biasanya untuk domain utama adalah /home/u247903474/domains/imamsafari.com/public_html/
        TARGET_DIR     = '/home/u247903474/domains/imamsafari.com/public_html/'
        
        // ID Credential di Jenkins yang menyimpan Private Key SSH
        SSH_CRED_ID    = 'hostinger-ssh-key'
    }

    stages {
        stage('Checkout') {
            steps {
                // Menarik kode terbaru dari branch main
                git branch: 'main', url: 'https://github.com/hnanfthr/notaris-web.git'
            }
        }
        
        stage('Deploy to Hostinger (SCP)') {
            steps {
                // Menggunakan plugin SSH Agent untuk otentikasi
                sshagent (credentials: ["${SSH_CRED_ID}"]) {
                    sh """
                    # Mendaftarkan IP Hostinger ke known_hosts agar tidak ditanya (yes/no)
                    mkdir -p ~/.ssh
                    ssh-keyscan -p ${HOSTINGER_PORT} ${HOSTINGER_IP} >> ~/.ssh/known_hosts
                    
                    echo "Mulai memaketkan file..."
                    # Membungkus file dari Workspace Jenkins menjadi zip/tar
                    # --exclude mencegah file yang tidak perlu ikut terupload
                    tar -czf deploy.tar.gz \\
                        --exclude='deploy.tar.gz' \\
                        --exclude='.git' \\
                        --exclude='.github' \\
                        --exclude='node_modules' \\
                        --exclude='tests' \\
                        --exclude='.env' \\
                        --exclude='Jenkinsfile' \\
                        --exclude='docker-compose.yml' \\
                        --exclude='README.md' \\
                        .
                        
                    echo "Mengirim file ke Hostinger..."
                    scp -P \${HOSTINGER_PORT} deploy.tar.gz \${HOSTINGER_USER}@\${HOSTINGER_IP}:\${TARGET_DIR}
                    
                    echo "Mengekstrak file di Hostinger..."
                    ssh -p \${HOSTINGER_PORT} \${HOSTINGER_USER}@\${HOSTINGER_IP} "cd \${TARGET_DIR} && tar -xzf deploy.tar.gz && rm deploy.tar.gz"
                    """
                }
            }
        }
        
        stage('Post-Deploy (Composer & Cache)') {
            steps {
                sshagent (credentials: ["${SSH_CRED_ID}"]) {
                    sh """
                    echo "Menjalankan Composer dan Artisan di Hostinger..."
                    
                    # SSH masuk ke Hostinger dan jalankan perintah
                    ssh -p \${HOSTINGER_PORT} \${HOSTINGER_USER}@\${HOSTINGER_IP} "cd \${TARGET_DIR} && composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache"
                    """
                }
            }
        }
    }
    
    post {
        success {
            echo '====================================================='
            echo '🎉 DEPLOYMENT BERHASIL! Website sudah terupdate.'
            echo '====================================================='
        }
        failure {
            echo '====================================================='
            echo '❌ DEPLOYMENT GAGAL. Silakan periksa log Jenkins.'
            echo '====================================================='
        }
    }
}
