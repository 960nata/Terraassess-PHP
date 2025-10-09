#!/bin/bash

# Terra Assessment Server Setup Script
# Server: 46.17.173.45:65002
# User: u7751686
# Domain: terraassess.com

echo "🚀 Terra Assessment Server Setup"
echo "================================="
echo "Server: 46.17.173.45:65002"
echo "User: u7751686"
echo "Domain: terraassess.com"
echo ""

# Commands to run on the server
cat << 'EOF'
# Connect to server
ssh -p 65002 u7751686@46.17.173.45

# Navigate to public_html
cd public_html

# Check current directory
pwd
ls -la

# Check if ZIP file exists
ls -la terra-assessment-production-*.zip

# Extract the ZIP file
unzip terra-assessment-production-*.zip

# Check extracted files
ls -la

# Navigate to the extracted folder (adjust name based on actual folder)
cd terra-assessment-production-*

# Check if we're in the right directory
pwd
ls -la

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# Check PHP version
php -v

# Check if composer is available
composer --version

# Generate application key
php artisan key:generate --force

# Run database migrations
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link

# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Check if npm is available
npm --version

# Install and build assets (if npm is available)
if command -v npm &> /dev/null; then
    npm install
    npm run build
else
    echo "⚠️  NPM not available, skipping asset compilation"
fi

# Set final permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 public

# Check if .htaccess exists
ls -la public/.htaccess

# Test if the application is working
php artisan --version

echo "✅ Setup completed!"
echo "🌐 Your application should be available at: https://terraassess.com"
echo "📊 Database: u7751686_terraassesment"

# Check database connection
php artisan tinker --execute="echo 'Database connection test: '; try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch(Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }"
EOF

echo ""
echo "📋 Copy and paste these commands one by one on your server:"
echo "============================================================"
