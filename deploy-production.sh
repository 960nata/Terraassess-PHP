#!/bin/bash

# Terra Assessment Production Deployment Script
# Domain: https://terraassess.com
# Database: u7751686_terraassesment

echo "🚀 Starting Terra Assessment Production Deployment..."

# Set production environment
echo "📝 Setting up production environment..."
cp production-config.env .env

# Generate application key if not exists
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Install/update composer dependencies
echo "📦 Installing composer dependencies..."
composer install --optimize-autoloader --no-dev

# Build assets (if using Vite)
echo "🎨 Building assets..."
npm run build

echo "✅ Production deployment completed!"
echo "🌐 Your application is ready at: https://terraassess.com"
echo "📊 Database: u7751686_terraassesment"
