#!/bin/bash

# Script untuk mempersiapkan project Laravel untuk shared hosting
# Menghapus file development yang tidak diperlukan

echo "🚀 Mempersiapkan project untuk shared hosting..."

# Buat folder untuk hosting
mkdir -p hosting-files

# Copy file yang diperlukan
echo "📁 Copying essential files..."
cp -r app hosting-files/
cp -r bootstrap hosting-files/
cp -r config hosting-files/
cp -r database hosting-files/
cp -r resources hosting-files/
cp -r routes hosting-files/
cp -r storage hosting-files/
cp -r vendor hosting-files/
cp -r public hosting-files/
cp artisan hosting-files/
cp composer.json hosting-files/
cp composer.lock hosting-files/

# Hapus file development dari resources
echo "🗑️ Removing development files..."
rm -rf hosting-files/resources/css/*.css
rm -rf hosting-files/resources/js/*.js
rm -rf hosting-files/resources/js/*.vue

# Copy hanya file yang sudah di-build
echo "📦 Copying built assets..."
cp -r public/build hosting-files/public/

# Hapus file yang tidak diperlukan
echo "🧹 Cleaning up unnecessary files..."
rm -f hosting-files/composer.json
rm -f hosting-files/composer.lock
rm -rf hosting-files/storage/logs/*
rm -rf hosting-files/storage/framework/cache/*
rm -rf hosting-files/storage/framework/sessions/*
rm -rf hosting-files/storage/framework/views/*

# Hapus SQLite database (tidak diperlukan untuk production)
echo "🗑️ Removing SQLite database..."
rm -f hosting-files/database/database.sqlite

# Buat .htaccess untuk root
echo "⚙️ Creating .htaccess for root directory..."
cat > hosting-files/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Hide sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>
EOF

# Buat .env template
echo "📝 Creating .env template..."
cat > hosting-files/.env.template << 'EOF'
APP_NAME="Terra Assessment"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
EOF

echo "✅ Project siap untuk hosting!"
echo "📁 Upload semua file dari folder 'hosting-files/' ke root directory hosting"
echo "🔑 Jangan lupa:"
echo "   1. Rename .env.template menjadi .env"
echo "   2. Update database credentials di .env"
echo "   3. Generate APP_KEY: php artisan key:generate"
echo "   4. Set permissions: chmod 755 storage/ bootstrap/cache/"
echo ""
echo "📊 Ukuran folder hosting:"
du -sh hosting-files/
