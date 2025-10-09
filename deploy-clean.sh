#!/bin/bash

# Deploy clean version to server
echo "Deploying clean version to server..."

# Create clean ZIP
echo "Creating clean production ZIP..."
./create-clean-production-zip.sh

# Upload to server
echo "Uploading to server..."
scp -P 65002 terraassess-production-clean.zip u7751686@46.17.173.45:~/public_html/

# Connect to server and deploy
echo "Deploying on server..."
ssh -p 65002 u7751686@46.17.173.45 << 'EOF'
cd ~/public_html/

# Backup current version
if [ -d "terraassess.com" ]; then
    echo "Backing up current version..."
    mv terraassess.com terraassess.com.backup.$(date +%Y%m%d_%H%M%S)
fi

# Extract new version
echo "Extracting new version..."
unzip -o -q terraassess-production-clean.zip
if [ -d "terraassess" ]; then
    mv terraassess terraassess.com
else
    # If no terraassess folder, create it and move files
    mkdir -p terraassess.com
    # Move all files except the zip file to terraassess.com
    find . -maxdepth 1 -type f ! -name "*.zip" -exec mv {} terraassess.com/ \;
    find . -maxdepth 1 -type d ! -name "terraassess.com" ! -name "." ! -name ".." -exec mv {} terraassess.com/ \;
fi

# Create missing directories
mkdir -p terraassess.com/storage/framework/cache
mkdir -p terraassess.com/storage/framework/sessions
mkdir -p terraassess.com/storage/framework/views
mkdir -p terraassess.com/storage/logs
mkdir -p terraassess.com/bootstrap/cache

# Set permissions
echo "Setting permissions..."
chmod -R 755 terraassess.com/
chmod -R 777 terraassess.com/storage/
chmod -R 777 terraassess.com/bootstrap/cache/

# Copy production files
echo "Copying production files..."
if [ -f "terraassess.com/.htaccess.production" ]; then
    cp terraassess.com/.htaccess.production terraassess.com/.htaccess
fi
if [ -f "terraassess.com/.env.production" ]; then
    cp terraassess.com/.env.production terraassess.com/.env
fi

# Generate app key
echo "Generating app key..."
cd terraassess.com
php artisan key:generate --force

# Clear cache
echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create user accounts
echo "Creating user accounts..."
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create Super Admin
if (!User::where('email', 'superadmin@terraassessment.com')->exists()) {
    User::create([
        'name' => 'Super Administrator',
        'email' => 'superadmin@terraassessment.com',
        'password' => Hash::make('password123'),
        'role' => 'SUPERADMIN',
        'isActive' => true
    ]);
    echo 'Super Admin created\n';
}

// Create Admin
if (!User::where('email', 'admin@terraassessment.com')->exists()) {
    User::create([
        'name' => 'Administrator',
        'email' => 'admin@terraassessment.com',
        'password' => Hash::make('password123'),
        'role' => 'ADMIN',
        'isActive' => true
    ]);
    echo 'Admin created\n';
}

// Create Teacher
if (!User::where('email', 'guru@terraassessment.com')->exists()) {
    User::create([
        'name' => 'Guru',
        'email' => 'guru@terraassessment.com',
        'password' => Hash::make('password123'),
        'role' => 'GURU',
        'isActive' => true
    ]);
    echo 'Teacher created\n';
}

// Create Student
if (!User::where('email', 'siswa@terraassessment.com')->exists()) {
    User::create([
        'name' => 'Siswa',
        'email' => 'siswa@terraassessment.com',
        'password' => Hash::make('password123'),
        'role' => 'SISWA',
        'isActive' => true
    ]);
    echo 'Student created\n';
}
"

echo "Deployment completed!"
echo "You can now access: https://terraassess.com"
echo "Login credentials:"
echo "Super Admin: superadmin@terraassessment.com / password123"
echo "Admin: admin@terraassessment.com / password123"
echo "Teacher: guru@terraassessment.com / password123"
echo "Student: siswa@terraassessment.com / password123"
EOF

echo "Deployment completed!"
