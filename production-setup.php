<?php
/**
 * Terra Assessment Production Setup Script
 * Domain: https://terraassess.com
 * Database: u7751686_terraassesment
 */

echo "🚀 Terra Assessment Production Setup\n";
echo "=====================================\n\n";

// Check PHP version
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    die("❌ PHP 8.1+ required. Current version: " . PHP_VERSION . "\n");
}

echo "✅ PHP Version: " . PHP_VERSION . "\n";

// Check required extensions
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    die("❌ Missing required extensions: " . implode(', ', $missing_extensions) . "\n");
}

echo "✅ All required extensions loaded\n";

// Database connection test
$db_config = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'u7751686_terraassesment',
    'username' => 'u7751686_terraassesment',
    'password' => '0Dg8fePmA;X(1xn%'
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['database']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "✅ Database connection successful\n";
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage() . "\n");
}

// Check if .env exists
if (!file_exists('.env')) {
    if (file_exists('production-config.env')) {
        copy('production-config.env', '.env');
        echo "✅ Created .env from production-config.env\n";
    } else {
        die("❌ No .env file found. Please create one from production-config.env\n");
    }
} else {
    echo "✅ .env file exists\n";
}

// Check storage permissions
$storage_paths = ['storage', 'storage/app', 'storage/framework', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache'];

foreach ($storage_paths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "✅ Created directory: $path\n";
    }
    
    if (!is_writable($path)) {
        chmod($path, 0755);
        echo "✅ Set permissions for: $path\n";
    }
}

echo "✅ Storage permissions configured\n";

// Generate application key if not exists
$env_content = file_get_contents('.env');
if (strpos($env_content, 'APP_KEY=base64:YOUR_APP_KEY_HERE') !== false) {
    $key = 'base64:' . base64_encode(random_bytes(32));
    $env_content = str_replace('APP_KEY=base64:YOUR_APP_KEY_HERE', 'APP_KEY=' . $key, $env_content);
    file_put_contents('.env', $env_content);
    echo "✅ Generated application key\n";
} else {
    echo "✅ Application key already exists\n";
}

// Check if storage link exists
if (!file_exists('public/storage')) {
    if (function_exists('symlink')) {
        symlink('../storage/app/public', 'public/storage');
        echo "✅ Created storage symlink\n";
    } else {
        echo "⚠️  Could not create storage symlink (symlink function not available)\n";
    }
} else {
    echo "✅ Storage symlink exists\n";
}

echo "\n🎉 Production setup completed successfully!\n";
echo "🌐 Your application is ready for: https://terraassess.com\n";
echo "📊 Database: u7751686_terraassesment\n\n";

echo "Next steps:\n";
echo "1. Run: php artisan migrate --force\n";
echo "2. Run: php artisan config:cache\n";
echo "3. Run: php artisan route:cache\n";
echo "4. Run: php artisan view:cache\n";
echo "5. Deploy to your web server\n\n";

echo "For detailed instructions, see: DEPLOYMENT_GUIDE.md\n";
?>
