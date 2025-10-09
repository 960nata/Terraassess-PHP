#!/bin/bash

echo "🔧 Fixing CSRF Token Issues..."

# Clear all caches
echo "1. Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate new application key
echo "2. Generating new application key..."
php artisan key:generate

# Clear config cache again
echo "3. Clearing config cache again..."
php artisan config:clear

# Check session directory
echo "4. Checking session directory..."
if [ ! -d "storage/framework/sessions" ]; then
    echo "Creating session directory..."
    mkdir -p storage/framework/sessions
    chmod 755 storage/framework/sessions
fi

# Set proper permissions
echo "5. Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Clear browser cache instructions
echo "6. Browser cache clearing instructions:"
echo "   - Press Ctrl+Shift+R (or Cmd+Shift+R on Mac)"
echo "   - Or clear browser data manually"
echo "   - Or try incognito/private mode"

echo "✅ CSRF fix completed!"
echo "🚀 Try logging in again now."
