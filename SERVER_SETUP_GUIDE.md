# 🚀 Terra Assessment - Server Setup Guide

## 📋 **Informasi Server**
- **Server**: 46.17.173.45:65002
- **User**: u7751686
- **Domain**: terraassess.com
- **Location**: public_html folder

## 🔗 **Step 1: Connect to Server**
```bash
ssh -p 65002 u7751686@46.17.173.45
```

## 📁 **Step 2: Navigate to public_html**
```bash
cd public_html
pwd
ls -la
```

## 📦 **Step 3: Check ZIP File**
```bash
# Check if ZIP file exists
ls -la terra-assessment-production-*.zip

# If not found, check all files
ls -la *.zip
```

## 📂 **Step 4: Extract ZIP File**
```bash
# Extract the ZIP file
unzip terra-assessment-production-*.zip

# Check extracted files
ls -la

# You should see a folder like: terra-assessment-production-20250930-123452
```

## 🗂️ **Step 5: Navigate to Application Folder**
```bash
# Navigate to the extracted folder (adjust name based on actual folder)
cd terra-assessment-production-*

# Check if we're in the right directory
pwd
ls -la

# You should see: app, bootstrap, config, database, public, etc.
```

## 🔧 **Step 6: Check Server Environment**
```bash
# Check PHP version (should be 8.1+)
php -v

# Check if composer is available
composer --version

# Check if npm is available (optional)
npm --version
```

## ⚙️ **Step 7: Configure Application**
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# Generate application key
php artisan key:generate --force

# Check if .env file is correct
cat .env | grep -E "(APP_URL|DB_|APP_ENV)"
```

## 🗄️ **Step 8: Setup Database**
```bash
# Run database migrations
php artisan migrate --force

# Check database connection
php artisan tinker --execute="echo 'Database connection test: '; try { DB::connection()->getPdo(); echo 'SUCCESS'; } catch(Exception \$e) { echo 'FAILED: ' . \$e->getMessage(); }"
```

## ⚡ **Step 9: Optimize for Production**
```bash
# Clear caches first
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link
```

## 📦 **Step 10: Install Dependencies**
```bash
# Install composer dependencies
composer install --optimize-autoloader --no-dev

# If npm is available, build assets
if command -v npm &> /dev/null; then
    npm install
    npm run build
    echo "✅ Assets built successfully"
else
    echo "⚠️  NPM not available, skipping asset compilation"
fi
```

## 🔐 **Step 11: Set Final Permissions**
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 public
chmod 644 .env

# Check .htaccess exists
ls -la public/.htaccess
```

## ✅ **Step 12: Test Application**
```bash
# Test if the application is working
php artisan --version

# Test routes
php artisan route:list | head -10

# Check if storage symlink exists
ls -la public/storage
```

## 🌐 **Step 13: Configure Web Server**

### **Option A: If using Apache (most common)**
```bash
# Check if .htaccess is in public folder
ls -la public/.htaccess

# If not, copy it
cp .htaccess.production public/.htaccess
```

### **Option B: If using Nginx**
You'll need to configure Nginx to point to the `public` folder as document root.

## 🔍 **Step 14: Verify Setup**
```bash
# Check all important files exist
ls -la .env
ls -la public/.htaccess
ls -la storage/logs
ls -la bootstrap/cache

# Test database connection
php artisan tinker --execute="echo 'Database: '; echo DB::connection()->getDatabaseName();"

# Check application status
php artisan about
```

## 🚨 **Troubleshooting**

### **If database connection fails:**
```bash
# Check database credentials in .env
cat .env | grep DB_

# Test connection manually
php -r "try { new PDO('mysql:host=localhost;dbname=u7751686_terraassesment', 'u7751686_terraassesment', '0Dg8fePmA;X(1xn%'); echo 'DB OK'; } catch(Exception \$e) { echo 'DB Error: ' . \$e->getMessage(); }"
```

### **If permissions issues:**
```bash
# Fix permissions
chmod -R 755 storage bootstrap/cache
chown -R u7751686:u7751686 storage bootstrap/cache
```

### **If composer issues:**
```bash
# Update composer
composer self-update
composer install --no-dev --optimize-autoloader
```

### **If artisan commands fail:**
```bash
# Check PHP version
php -v

# Check if all required extensions are installed
php -m | grep -E "(pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|fileinfo)"
```

## 🎉 **Final Steps**

1. **Test the website**: Visit https://terraassess.com
2. **Check logs**: `tail -f storage/logs/laravel.log`
3. **Monitor performance**: Check server resources

## 📞 **Support Commands**

```bash
# Check application status
php artisan about

# Check routes
php artisan route:list

# Check configuration
php artisan config:show

# Clear all caches
php artisan optimize:clear

# Check storage
php artisan storage:link
```

---

**🎯 Expected Result**: Your Terra Assessment application should be live at https://terraassess.com with all features working!

**📊 Database**: u7751686_terraassesment
**🔧 Environment**: Production
**⚡ Performance**: Optimized
