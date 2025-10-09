#!/bin/bash

# Quick Setup Script for Terra Assessment
# Run this on the server after extracting the ZIP file

echo "🚀 Terra Assessment Quick Setup"
echo "==============================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

echo "✅ Found Laravel application"

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Cache for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Install composer dependencies
echo "📦 Installing composer dependencies..."
composer install --optimize-autoloader --no-dev

# Set final permissions
echo "🔐 Setting final permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 public

# Test database connection
echo "🔍 Testing database connection..."
php artisan tinker --execute="echo 'Database connection test: '; try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch(Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }"

# Check application status
echo "📊 Application status:"
php artisan --version

echo ""
echo "✅ Setup completed!"
echo "🌐 Your application should be available at: https://terraassess.com"
echo "📊 Database: u7751686_terraassesment"
echo ""
echo "🔍 To check logs: tail -f storage/logs/laravel.log"
echo "🔍 To test routes: php artisan route:list"
