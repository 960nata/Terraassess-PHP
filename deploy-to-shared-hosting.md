# 🚀 Deploy Laravel ke Shared Hosting

## 📋 Checklist Sebelum Deploy

### ✅ 1. Build Assets untuk Production (Lokal)
```bash
# Build semua CSS/JS ke static files
npm run build

# Hasil build akan tersimpan di public/build/
# - assets/app-776845ad.js (854KB)
# - assets/app-9dd465b7.css (42KB) 
# - assets/stars-290a7fa4.css (9KB)
# - assets/tailwind-179954eb.css (57B)
# - assets/galaxy-theme-43210177.css (11KB)
# - assets/design-system-1b1f8ec7.css (18KB)
```

### ✅ 2. Generate App Key
```bash
php artisan key:generate
```

### ✅ 3. Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🚫 TIDAK PERLU NODE.JS DI HOSTING!

**✅ Assets sudah di-build menjadi static files:**
- CSS: Sudah di-compile dan minified
- JS: Sudah di-bundle dan minified  
- Tailwind: Sudah di-purge dan optimized
- Vite: Hanya untuk development, tidak perlu di production

## 📁 File yang Perlu Di-upload

### ✅ Upload ke Root Directory (public_html):
```
public/
├── index.php
├── .htaccess
├── build/
│   ├── assets/
│   └── manifest.json
├── css/
├── js/
└── storage/ (symlink ke ../storage/app/public)

app/
bootstrap/
config/
database/
resources/
routes/
storage/
vendor/
artisan
composer.json
composer.lock
```

### ❌ JANGAN Upload:
- node_modules/ (TIDAK PERLU - assets sudah di-build)
- package.json (TIDAK PERLU - hanya untuk development)
- package-lock.json (TIDAK PERLU - hanya untuk development)
- vite.config.js (TIDAK PERLU - hanya untuk development)
- tailwind.config.js (TIDAK PERLU - hanya untuk development)
- .env (buat manual di hosting)
- .git/
- tests/
- storage/logs/
- storage/framework/cache/
- storage/framework/sessions/
- storage/framework/views/

## 🔧 Konfigurasi di Shared Hosting

### 1. Database Setup (MySQL ONLY)
- Buat database MySQL di cPanel
- Import database dari local (jika ada)
- Update .env dengan kredensial database hosting
- **SQLite sudah dihapus** - hanya menggunakan MySQL

### 2. File .env
```env
APP_NAME="Terra Assessment"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 3. File Permissions
```bash
chmod 755 storage/
chmod 755 bootstrap/cache/
chmod 644 .env
```

### 4. .htaccess untuk Root
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

## 🎯 Struktur Upload ke Hosting

```
public_html/
├── .htaccess (redirect ke public/)
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── build/
│   └── storage/ (symlink)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
└── .env
```

## ⚠️ Troubleshooting

### Error 500
- Check file permissions
- Check .env configuration
- Check error logs di cPanel

### CSS/JS tidak load
- Pastikan build/ folder ter-upload
- Check Vite manifest.json

### Database Error
- Check database credentials
- Run migrations: `php artisan migrate`

## 🚀 Post-Deploy Commands

```bash
# Di terminal hosting (jika ada SSH)
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📱 Testing

1. Akses domain utama
2. Test login functionality
3. Test file upload
4. Test responsive design
5. Check console untuk error

## 🔒 Security

- Set APP_DEBUG=false
- Set APP_ENV=production
- Check file permissions
- Enable HTTPS
- Update .env dengan credentials yang benar
