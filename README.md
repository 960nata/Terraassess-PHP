# 🎓 Elass 2 - Learning Management System

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Elass 2 adalah Learning Management System (LMS) modern yang dibangun dengan Laravel 10 dan Vue.js 3. Aplikasi ini menyediakan platform lengkap untuk manajemen pembelajaran online dengan fitur tugas, ujian, materi, dan dashboard interaktif.

## ✨ Features

### 🎯 **Core Features**
- **Dashboard Interaktif** - Statistik real-time dengan Chart.js
- **Manajemen Tugas** - Individual, Kelompok, Quiz, dan Pilihan Ganda
- **Sistem Ujian** - Essay dan Multiple Choice dengan timer
- **Manajemen Materi** - Upload file dan konten multimedia
- **Role-based Access** - Admin, Pengajar, dan Siswa
- **Import/Export** - Data Excel untuk bulk operations

### 🚀 **Modern Features**
- **Vue.js SPA** - Single Page Application dengan Vue 3
- **RESTful API** - Complete API dengan Laravel Sanctum
- **Real-time Updates** - Live notifications dan updates
- **Responsive Design** - Mobile-first approach
- **File Management** - Secure file upload dan storage
- **Advanced Search** - Full-text search capabilities

### 🔧 **Technical Features**
- **Service Layer Architecture** - Clean separation of concerns
- **Repository Pattern** - Efficient data access layer
- **Form Validation** - Comprehensive input validation
- **Caching System** - Redis dan file-based caching
- **Testing Suite** - Unit dan Feature tests
- **API Documentation** - Complete API documentation

## 🛠️ Tech Stack

### **Backend**
- **Laravel 10** - PHP Framework
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Excel** - Import/Export functionality
- **Redis** - Caching (optional)

### **Frontend**
- **Vue.js 3** - Progressive JavaScript Framework
- **Vue Router** - Client-side routing
- **Pinia** - State management
- **Axios** - HTTP client
- **Bootstrap 5** - CSS Framework
- **Chart.js** - Data visualization

### **Development Tools**
- **Vite** - Build tool
- **PHPUnit** - Testing framework
- **Laravel Pint** - Code style fixer
- **Git** - Version control

## 📋 Requirements

- **PHP** >= 8.1
- **Composer** >= 2.0
- **Node.js** >= 16.0
- **NPM** >= 8.0
- **MySQL** >= 8.0
- **Redis** (optional, for caching)

## 🚀 Installation

### **1. Clone Repository**
```bash
git clone https://github.com/yourusername/elass2.git
cd elass2
```

### **2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### **3. Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elass2
DB_USERNAME=root
DB_PASSWORD=
```

### **4. Database Setup**
```bash
# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### **5. Storage Setup**
```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### **6. Build Assets**
```bash
# Development
npm run dev

# Production
npm run build
```

### **7. Start Server**
```bash
# Start Laravel server
php artisan serve

# Start queue worker (optional)
php artisan queue:work
```

## 🔧 Configuration

### **Environment Variables**
```env
# Application
APP_NAME="Elass 2"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=elass2
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Cache
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### **File Upload Configuration**
```php
// config/filesystems.php
'max_file_size' => 10240, // 10MB
'allowed_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']
```

## 📚 API Documentation

### **Authentication**
```bash
# Login
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}

# Get user
GET /api/user
Authorization: Bearer {token}
```

### **Tugas (Assignments)**
```bash
# Get all tugas
GET /api/tugas

# Create tugas
POST /api/tugas
{
  "judul": "Tugas Matematika",
  "deskripsi": "Kerjakan soal-soal berikut",
  "deadline": "2024-01-15T23:59:59.000000Z",
  "tipe_tugas": "individual",
  "kelas_mapel_id": 1
}

# Get tugas by ID
GET /api/tugas/{id}

# Update tugas
PUT /api/tugas/{id}

# Delete tugas
DELETE /api/tugas/{id}
```

### **Statistics**
```bash
# Get tugas statistics
GET /api/tugas/statistics

# Search tugas
GET /api/tugas/search?q=matematika

# Upcoming deadlines
GET /api/tugas/upcoming-deadlines?days=7
```

## 🧪 Testing

### **Run Tests**
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TugasServiceTest

# Run with coverage
php artisan test --coverage
```

### **Test Structure**
```
tests/
├── Unit/
│   └── Services/
│       └── TugasServiceTest.php
├── Feature/
│   └── Api/
│       └── TugasApiTest.php
└── TestCase.php
```

## 📁 Project Structure

```
elass2/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   └── ...
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Services/
│   ├── Repositories/
│   └── Exceptions/
├── resources/
│   ├── js/
│   │   ├── components/
│   │   ├── stores/
│   │   └── app.js
│   └── views/
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
├── docs/
└── public/
```

## 🚀 Deployment

### **Production Setup**
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/elass2/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

## 🙏 Acknowledgments

- Laravel Community
- Vue.js Team
- Bootstrap Team
- All contributors

## 📞 Support

If you have any questions or need help, please:

1. Check the [Issues](https://github.com/yourusername/elass2/issues) page
2. Create a new issue if your problem isn't already reported
3. Contact us at support@elass2.com

---

⭐ **Star this repository if you found it helpful!**