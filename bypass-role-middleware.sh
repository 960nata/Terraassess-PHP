#!/bin/bash

echo "🔧 Terra Assessment - Role Middleware Bypass Script"
echo "=================================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

print_status "Starting role middleware bypass process..."

# 1. Backup original routes file
print_status "1. Creating backup of routes file..."
cp routes/web.php routes/web.php.backup
if [ $? -eq 0 ]; then
    print_success "Routes file backed up successfully"
else
    print_error "Failed to backup routes file"
    exit 1
fi

# 2. Create temporary routes file with bypass middleware
print_status "2. Creating temporary routes with bypass middleware..."

# Create a script to replace role middleware with role.bypass
cat > temp_replace_middleware.php << 'EOF'
<?php
$routesFile = 'routes/web.php';
$content = file_get_contents($routesFile);

// Replace role middleware with role.bypass for testing
$content = str_replace("'role:", "'role.bypass:", $content);
$content = str_replace('"role:', '"role.bypass:', $content);

file_put_contents($routesFile, $content);
echo "Middleware replaced successfully\n";
?>
EOF

php temp_replace_middleware.php
rm temp_replace_middleware.php

if [ $? -eq 0 ]; then
    print_success "Middleware replaced with bypass version"
else
    print_error "Failed to replace middleware"
    exit 1
fi

# 3. Clear caches
print_status "3. Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

if [ $? -eq 0 ]; then
    print_success "Caches cleared successfully"
else
    print_warning "Some cache clearing failed, but continuing..."
fi

# 4. Create debug routes
print_status "4. Adding debug routes..."

# Add debug routes to web.php
cat >> routes/web.php << 'EOF'

// Debug routes for role testing
Route::get('/debug-role-test', function() {
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'authenticated' => false,
            'message' => 'User not authenticated'
        ]);
    }
    
    return response()->json([
        'authenticated' => true,
        'user_id' => $user->id,
        'user_email' => $user->email,
        'user_role_id' => $user->roles_id,
        'user_role_name' => $user->Role ? $user->Role->name : 'No role found',
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
})->middleware('auth')->name('debug.role.test');

Route::get('/debug-middleware-test', function() {
    return response()->json([
        'message' => 'Middleware bypass is working!',
        'timestamp' => now(),
        'user' => auth()->user() ? auth()->user()->email : 'Not authenticated'
    ]);
})->middleware(['auth', 'role.bypass:superadmin'])->name('debug.middleware.test');
EOF

print_success "Debug routes added"

# 5. Final verification
print_status "5. Running final verification..."

# Check if routes file was modified
if grep -q "role.bypass" routes/web.php; then
    print_success "Role bypass middleware is active"
else
    print_error "Role bypass middleware not found"
fi

# 6. Summary
echo ""
echo "=================================================="
print_success "Role middleware bypass completed!"
echo "=================================================="
echo ""
print_status "What was changed:"
echo "1. All 'role:' middleware replaced with 'role.bypass:'"
echo "2. Added debug routes for testing"
echo "3. Cleared all caches"
echo ""
print_status "Testing URLs:"
echo "- Debug Role Test: http://localhost:8000/debug-role-test"
echo "- Debug Middleware Test: http://localhost:8000/debug-middleware-test"
echo ""
print_warning "IMPORTANT:"
echo "1. This bypasses ALL role restrictions"
echo "2. Use ONLY for testing and debugging"
echo "3. Restore original routes when done: cp routes/web.php.backup routes/web.php"
echo ""
print_status "To restore original middleware:"
echo "cp routes/web.php.backup routes/web.php"
echo "php artisan route:clear"
echo ""
print_success "Bypass script completed! 🚀"
