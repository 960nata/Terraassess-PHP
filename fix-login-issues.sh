#!/bin/bash

echo "🔧 Terra Assessment - Login Issues Fix Script"
echo "=============================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Starting login issues fix process..."

# 1. Clear all caches
print_status "1. Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

if [ $? -eq 0 ]; then
    print_success "All caches cleared successfully"
else
    print_error "Failed to clear caches"
    exit 1
fi

# 2. Generate new application key
print_status "2. Generating new application key..."
php artisan key:generate

if [ $? -eq 0 ]; then
    print_success "Application key generated successfully"
else
    print_error "Failed to generate application key"
    exit 1
fi

# 3. Clear config cache again
print_status "3. Clearing config cache again..."
php artisan config:clear

if [ $? -eq 0 ]; then
    print_success "Config cache cleared"
else
    print_warning "Config cache clear failed, but continuing..."
fi

# 4. Check and create session directory
print_status "4. Checking session directory..."
if [ ! -d "storage/framework/sessions" ]; then
    print_status "Creating session directory..."
    mkdir -p storage/framework/sessions
    chmod 755 storage/framework/sessions
    print_success "Session directory created"
else
    print_success "Session directory already exists"
fi

# 5. Set proper permissions
print_status "5. Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

if [ $? -eq 0 ]; then
    print_success "Permissions set successfully"
else
    print_warning "Some permission changes failed, but continuing..."
fi

# 6. Check .env file
print_status "6. Checking .env configuration..."
if [ ! -f ".env" ]; then
    print_warning ".env file not found. Creating from .env.example..."
    cp .env.example .env
    php artisan key:generate
    print_success ".env file created"
else
    print_success ".env file exists"
fi

# 7. Run database migrations (if needed)
print_status "7. Checking database migrations..."
php artisan migrate:status > /dev/null 2>&1
if [ $? -eq 0 ]; then
    print_success "Database connection is working"
else
    print_warning "Database connection issues detected. Please check your .env file"
fi

# 8. Create debug routes (if they don't exist)
print_status "8. Checking debug routes..."
if grep -q "debug-csrf" routes/web.php; then
    print_success "Debug routes already exist"
else
    print_warning "Debug routes not found. You may need to add them manually"
fi

# 9. Final verification
print_status "9. Running final verification..."

# Check if storage is writable
if [ -w "storage/" ]; then
    print_success "Storage directory is writable"
else
    print_error "Storage directory is not writable"
fi

# Check if bootstrap/cache is writable
if [ -w "bootstrap/cache/" ]; then
    print_success "Bootstrap cache directory is writable"
else
    print_error "Bootstrap cache directory is not writable"
fi

# 10. Summary
echo ""
echo "=============================================="
print_success "Login issues fix completed!"
echo "=============================================="
echo ""
print_status "Next steps:"
echo "1. Start the development server: php artisan serve"
echo "2. Access the application: http://localhost:8000"
echo "3. Test login functionality"
echo "4. If issues persist, check:"
echo "   - Browser console for JavaScript errors"
echo "   - Network tab for failed requests"
echo "   - Laravel logs: storage/logs/laravel.log"
echo ""
print_status "Debug tools available:"
echo "- CSRF Debug: http://localhost:8000/debug-csrf"
echo "- User Debug: http://localhost:8000/debug-user"
echo ""
print_warning "If you still experience issues:"
echo "1. Clear browser cache (Ctrl+Shift+R)"
echo "2. Try incognito/private mode"
echo "3. Check browser console for errors"
echo "4. Verify .env configuration"
echo ""
print_success "Fix script completed successfully! 🚀"
