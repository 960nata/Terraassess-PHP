#!/bin/bash

# Terra Assessment Production ZIP Creator
# Domain: https://terraassess.com
# Database: u7751686_terraassesment

echo "🚀 Creating Terra Assessment Production ZIP..."
echo "=============================================="

# Set variables
PROJECT_NAME="terra-assessment-production"
ZIP_NAME="${PROJECT_NAME}-$(date +%Y%m%d-%H%M%S).zip"
TEMP_DIR="temp-production"

# Clean up previous temp directory
if [ -d "$TEMP_DIR" ]; then
    rm -rf "$TEMP_DIR"
fi

# Create temp directory
mkdir "$TEMP_DIR"

echo "📁 Copying files to temporary directory..."

# Copy essential files and directories
cp -r app "$TEMP_DIR/"
cp -r bootstrap "$TEMP_DIR/"
cp -r config "$TEMP_DIR/"
cp -r database "$TEMP_DIR/"
cp -r lang "$TEMP_DIR/"
cp -r public "$TEMP_DIR/"
cp -r resources "$TEMP_DIR/"
cp -r routes "$TEMP_DIR/"
cp -r storage "$TEMP_DIR/"

# Copy essential files
cp artisan "$TEMP_DIR/"
cp composer.json "$TEMP_DIR/"
cp package.json "$TEMP_DIR/"
cp package-lock.json "$TEMP_DIR/"
cp tailwind.config.js "$TEMP_DIR/"
cp vite.config.js "$TEMP_DIR/"
cp pint.json "$TEMP_DIR/"
cp phpunit.xml "$TEMP_DIR/"

# Copy production configuration files
cp production-config.env "$TEMP_DIR/.env"
cp public/.htaccess.production "$TEMP_DIR/public/.htaccess"
cp DEPLOYMENT_GUIDE.md "$TEMP_DIR/"
cp PRODUCTION_READY.md "$TEMP_DIR/"

# Create deployment instructions
cat > "$TEMP_DIR/DEPLOY_INSTRUCTIONS.txt" << 'EOF'
🚀 TERRA ASSESSMENT - DEPLOYMENT INSTRUCTIONS
=============================================

Domain: https://terraassess.com
Database: u7751686_terraassesment

QUICK DEPLOYMENT:
================

1. Upload all files to your web server
2. Set document root to 'public' folder
3. Run these commands on your server:

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
   
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   
   # Set permissions
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache

4. Configure your web server to point to the 'public' directory
5. Ensure mod_rewrite is enabled (Apache) or proper nginx config
6. Test your application at https://terraassess.com

For detailed instructions, see DEPLOYMENT_GUIDE.md

IMPORTANT NOTES:
- Database credentials are already configured
- Environment is set to production
- All security optimizations are applied
- HTTPS enforcement is enabled

Support: Check logs in storage/logs/laravel.log if issues occur
EOF

# Create .htaccess for production
cat > "$TEMP_DIR/public/.htaccess" << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always append X-Frame-Options SAMEORIGIN
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Performance Optimizations
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
</IfModule>

# Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Hide sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>

<Files "package.json">
    Order allow,deny
    Deny from all
</Files>

<Files "package-lock.json">
    Order allow,deny
    Deny from all
</Files>
EOF

# Create production .env file
cat > "$TEMP_DIR/.env" << 'EOF'
APP_NAME="Terra Assessment"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://terraassess.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u7751686_terraassesment
DB_USERNAME=u7751686_terraassesment
DB_PASSWORD=0Dg8fePmA;X(1xn%

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@terraassess.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

# Clean up storage and cache directories
echo "🧹 Cleaning up storage and cache directories..."
rm -rf "$TEMP_DIR/storage/logs"/*
rm -rf "$TEMP_DIR/storage/framework/cache"/*
rm -rf "$TEMP_DIR/storage/framework/sessions"/*
rm -rf "$TEMP_DIR/storage/framework/views"/*
rm -rf "$TEMP_DIR/bootstrap/cache"/*

# Create necessary directories
mkdir -p "$TEMP_DIR/storage/logs"
mkdir -p "$TEMP_DIR/storage/framework/cache"
mkdir -p "$TEMP_DIR/storage/framework/sessions"
mkdir -p "$TEMP_DIR/storage/framework/views"
mkdir -p "$TEMP_DIR/bootstrap/cache"

# Create .gitkeep files
touch "$TEMP_DIR/storage/logs/.gitkeep"
touch "$TEMP_DIR/storage/framework/cache/.gitkeep"
touch "$TEMP_DIR/storage/framework/sessions/.gitkeep"
touch "$TEMP_DIR/storage/framework/views/.gitkeep"
touch "$TEMP_DIR/bootstrap/cache/.gitkeep"

echo "📦 Creating ZIP file..."

# Create ZIP file
cd "$TEMP_DIR"
zip -r "../$ZIP_NAME" . -x "*.DS_Store" "*.git*" "*.log" "*.tmp" "*.temp"
cd ..

# Clean up temp directory
rm -rf "$TEMP_DIR"

# Get file size
FILE_SIZE=$(du -h "$ZIP_NAME" | cut -f1)

echo "✅ Production ZIP created successfully!"
echo "📁 File: $ZIP_NAME"
echo "📊 Size: $FILE_SIZE"
echo ""
echo "🚀 Ready for deployment to https://terraassess.com"
echo "📖 See DEPLOY_INSTRUCTIONS.txt inside the ZIP for deployment steps"
echo ""
echo "📋 Contents included:"
echo "   ✅ Laravel application files"
echo "   ✅ Production configuration"
echo "   ✅ Database configuration"
echo "   ✅ Security optimizations"
echo "   ✅ Performance optimizations"
echo "   ✅ Deployment instructions"
echo "   ✅ Clean storage directories"
echo ""
echo "🎉 Your Terra Assessment is ready for production!"
