# Terra Assessment - Production Deployment Guide

## 🌐 Domain Configuration
- **Domain**: https://terraassess.com
- **Database**: u7751686_terraassesment
- **Environment**: Production

## 📋 Pre-Deployment Checklist

### 1. Server Requirements
- PHP 8.1+ (Current: PHP 8.4.8)
- MySQL 5.7+ or MariaDB 10.2+
- Apache/Nginx with mod_rewrite enabled
- Composer
- Node.js & NPM (for asset compilation)

### 2. Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u7751686_terraassesment
DB_USERNAME=u7751686_terraassesment
DB_PASSWORD=0Dg8fePmA;X(1xn%
```

## 🚀 Deployment Steps

### Step 1: Upload Files
1. Upload all project files to your web server
2. Ensure the `public` folder is your document root
3. Place all other files outside the web root for security

### Step 2: Environment Configuration
1. Copy `production-config.env` to `.env`
2. Update the following in `.env`:
   ```env
   APP_URL=https://terraassess.com
   APP_ENV=production
   APP_DEBUG=false
   ```

### Step 3: Generate Application Key
```bash
php artisan key:generate --force
```

### Step 4: Database Setup
```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force
```

### Step 5: Optimize for Production
```bash
# Clear caches
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

### Step 6: Install Dependencies
```bash
# Install composer dependencies
composer install --optimize-autoloader --no-dev

# Install and build NPM assets
npm install
npm run build
```

### Step 7: Set Permissions
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 8: Configure Web Server

#### Apache Configuration
1. Copy `.htaccess.production` to `public/.htaccess`
2. Ensure mod_rewrite is enabled
3. Set document root to `public` folder

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen 443 ssl;
    server_name terraassess.com www.terraassess.com;
    root /path/to/your/project/public;
    index index.php;

    # SSL Configuration (if using HTTPS)
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔧 Quick Deployment Script

Run the automated deployment script:
```bash
chmod +x deploy-production.sh
./deploy-production.sh
```

## 🔒 Security Considerations

### 1. File Permissions
- Set `storage` and `bootstrap/cache` to 755
- Set `.env` to 600
- Ensure web server can read but not write to most files

### 2. Environment Security
- Never commit `.env` file to version control
- Use strong database passwords
- Enable HTTPS with SSL certificates
- Set `APP_DEBUG=false` in production

### 3. Database Security
- Use strong passwords
- Limit database user permissions
- Enable SSL connections if possible
- Regular backups

## 📊 Performance Optimization

### 1. Caching
- Enable route caching: `php artisan route:cache`
- Enable config caching: `php artisan config:cache`
- Enable view caching: `php artisan view:cache`

### 2. Asset Optimization
- Minify CSS and JavaScript
- Optimize images
- Use CDN for static assets
- Enable Gzip compression

### 3. Database Optimization
- Add proper indexes
- Optimize queries
- Use database connection pooling

## 🐛 Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check file permissions
   - Verify `.env` configuration
   - Check Apache/Nginx error logs

2. **Database Connection Error**
   - Verify database credentials
   - Check if database server is running
   - Ensure database exists

3. **Asset Loading Issues**
   - Run `php artisan storage:link`
   - Check file permissions
   - Verify asset paths

4. **Route Not Found**
   - Clear route cache: `php artisan route:clear`
   - Check `.htaccess` configuration

## 📞 Support

For deployment issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server error logs
3. PHP error logs
4. Database connection logs

## ✅ Post-Deployment Verification

1. Visit https://terraassess.com
2. Test user registration/login
3. Verify all features work correctly
4. Check database connectivity
5. Test file uploads
6. Verify email functionality (if configured)

---

**Deployment Date**: $(date)
**Laravel Version**: 10.49.0
**PHP Version**: 8.4.8
**Database**: MySQL u7751686_terraassesment
