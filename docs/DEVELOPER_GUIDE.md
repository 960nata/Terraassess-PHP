# 🛠️ Developer Guide - Sistem Penilaian & Feedback Terintegrasi

## 📋 Daftar Isi

1. [Setup Development Environment](#setup-development-environment)
2. [Architecture Overview](#architecture-overview)
3. [Database Schema](#database-schema)
4. [API Documentation](#api-documentation)
5. [Testing Guide](#testing-guide)
6. [Deployment Guide](#deployment-guide)
7. [Contributing Guidelines](#contributing-guidelines)

---

## 🚀 Setup Development Environment

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+
- Redis (optional, for caching)

### Installation Steps

#### 1. Clone Repository
```bash
git clone https://github.com/your-repo/grading-system.git
cd grading-system
```

#### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# Node.js dependencies
npm install
```

#### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grading_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Create storage link
php artisan storage:link
```

#### 5. Build Assets
```bash
# Development build
npm run dev

# Production build
npm run build
```

#### 6. Start Development Server
```bash
# Start Laravel server
php artisan serve

# Start queue worker (optional)
php artisan queue:work
```

---

## 🏗️ Architecture Overview

### System Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Blade + JS)  │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   CSS/JS        │    │   Controllers   │    │   Migrations    │
│   Assets        │    │   Models        │    │   Seeders       │
└─────────────────┘    │   Services      │    └─────────────────┘
                       │   Middleware    │
                       └─────────────────┘
```

### Key Components

#### 1. **Models**
- `UserTugas` - Main grading model
- `RubrikPenilaian` - Rubric definitions
- `UserTugasRubrik` - Rubric scores
- `NilaiHistory` - Grade revision history
- `Notification` - System notifications

#### 2. **Controllers**
- `TugasController` - Main grading logic
- `RubrikController` - Rubric management
- `NotificationController` - Notification handling
- `AnalyticsController` - Analytics and reporting

#### 3. **Services**
- `ReportService` - PDF generation
- `NotificationService` - Notification management
- `AnalyticsService` - Data analytics

#### 4. **Middleware**
- `Role` - Role-based access control
- `GranularRbacProtection` - Granular permissions

---

## 🗄️ Database Schema

### Core Tables

#### `user_tugas`
```sql
CREATE TABLE user_tugas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    tugas_id BIGINT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    nilai INT NULL,
    komentar TEXT NULL,
    dinilai_oleh BIGINT NULL,
    dinilai_pada TIMESTAMP NULL,
    revisi_ke INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tugas_id) REFERENCES tugas(id),
    FOREIGN KEY (dinilai_oleh) REFERENCES users(id)
);
```

#### `rubrik_penilaian`
```sql
CREATE TABLE rubrik_penilaian (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    tugas_id BIGINT NOT NULL,
    aspek VARCHAR(255) NOT NULL,
    bobot INT NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (tugas_id) REFERENCES tugas(id)
);
```

#### `user_tugas_rubrik`
```sql
CREATE TABLE user_tugas_rubrik (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_tugas_id BIGINT NOT NULL,
    rubrik_id BIGINT NOT NULL,
    nilai INT NOT NULL,
    komentar_aspek TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_tugas_id) REFERENCES user_tugas(id),
    FOREIGN KEY (rubrik_id) REFERENCES rubrik_penilaian(id)
);
```

#### `nilai_history`
```sql
CREATE TABLE nilai_history (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_tugas_id BIGINT NOT NULL,
    nilai_lama INT NOT NULL,
    nilai_baru INT NOT NULL,
    komentar_lama TEXT NULL,
    komentar_baru TEXT NULL,
    diubah_oleh BIGINT NOT NULL,
    alasan_revisi TEXT NULL,
    diubah_pada TIMESTAMP NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_tugas_id) REFERENCES user_tugas(id),
    FOREIGN KEY (diubah_oleh) REFERENCES users(id)
);
```

#### `notifications`
```sql
CREATE TABLE notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    related_type VARCHAR(50) NULL,
    related_id BIGINT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Indexes
```sql
-- Performance indexes
CREATE INDEX idx_user_tugas_user_id ON user_tugas(user_id);
CREATE INDEX idx_user_tugas_tugas_id ON user_tugas(tugas_id);
CREATE INDEX idx_user_tugas_dinilai_oleh ON user_tugas(dinilai_oleh);
CREATE INDEX idx_rubrik_penilaian_tugas_id ON rubrik_penilaian(tugas_id);
CREATE INDEX idx_user_tugas_rubrik_user_tugas_id ON user_tugas_rubrik(user_tugas_id);
CREATE INDEX idx_user_tugas_rubrik_rubrik_id ON user_tugas_rubrik(rubrik_id);
CREATE INDEX idx_nilai_history_user_tugas_id ON nilai_history(user_tugas_id);
CREATE INDEX idx_nilai_history_diubah_oleh ON nilai_history(diubah_oleh);
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
```

---

## 🔌 API Documentation

### Authentication
All API endpoints require authentication via session or token.

### Base URL
```
https://your-domain.com/api
```

### Endpoints

#### Rubrik API

##### Get Rubrik for Task
```http
GET /api/rubrik/{tugasId}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "tugas_id": 1,
            "aspek": "Isi & Analisis",
            "bobot": 40,
            "deskripsi": "Kedalaman analisis dan relevansi isi"
        }
    ]
}
```

##### Create Rubrik
```http
POST /rubrik/store
Content-Type: application/json

{
    "tugas_id": 1,
    "aspek": ["Isi & Analisis", "Struktur & Organisasi"],
    "bobot": [40, 60],
    "deskripsi": ["Kedalaman analisis", "Keruntutan penyajian"]
}
```

#### Analytics API

##### Get Analytics Data
```http
GET /api/analytics/data?type=distribution
```

**Query Parameters:**
- `type`: `distribution|trends|frequency|performance`

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "grade": "A (90-100)",
            "count": 15,
            "percentage": 30.0
        }
    ]
}
```

#### Notifications API

##### Get Unread Count
```http
GET /api/notifications/unread-count
```

**Response:**
```json
{
    "unreadCount": 5
}
```

##### Get Latest Notifications
```http
GET /api/notifications/latest
```

**Response:**
```json
{
    "notifications": [
        {
            "id": 1,
            "title": "Tugas Dinilai",
            "message": "Tugas 'Essay Analisis' telah dinilai",
            "type": "success",
            "is_read": false,
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "unreadCount": 5
}
```

### Error Responses

#### 400 Bad Request
```json
{
    "error": "Invalid request data",
    "message": "Total bobot harus 100%"
}
```

#### 403 Forbidden
```json
{
    "error": "Unauthorized access",
    "message": "You don't have permission to access this resource"
}
```

#### 404 Not Found
```json
{
    "error": "Resource not found",
    "message": "Tugas not found"
}
```

#### 500 Internal Server Error
```json
{
    "error": "Internal server error",
    "message": "An unexpected error occurred"
}
```

---

## 🧪 Testing Guide

### Running Tests

#### Unit Tests
```bash
# Run all unit tests
php artisan test --testsuite=Unit

# Run specific test class
php artisan test tests/Unit/Models/RubrikPenilaianTest.php

# Run with coverage
php artisan test --coverage
```

#### Feature Tests
```bash
# Run all feature tests
php artisan test --testsuite=Feature

# Run specific test class
php artisan test tests/Feature/GradingWorkflowTest.php
```

#### Browser Tests (Laravel Dusk)
```bash
# Install Dusk
php artisan dusk:install

# Run browser tests
php artisan dusk
```

### Test Structure

#### Unit Tests
```
tests/Unit/
├── Models/
│   ├── RubrikPenilaianTest.php
│   ├── UserTugasRubrikTest.php
│   ├── NilaiHistoryTest.php
│   └── NotificationTest.php
├── Controllers/
│   ├── RubrikControllerTest.php
│   └── AnalyticsControllerTest.php
└── Services/
    └── ReportServiceTest.php
```

#### Feature Tests
```
tests/Feature/
├── GradingWorkflowTest.php
├── NotificationSystemTest.php
├── ExportImportTest.php
└── AnalyticsTest.php
```

### Writing Tests

#### Model Test Example
```php
<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\RubrikPenilaian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RubrikPenilaianTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_tugas()
    {
        $tugas = Tugas::factory()->create();
        $rubrik = RubrikPenilaian::factory()->create(['tugas_id' => $tugas->id]);

        $this->assertInstanceOf(Tugas::class, $rubrik->tugas);
        $this->assertEquals($tugas->id, $rubrik->tugas->id);
    }
}
```

#### Feature Test Example
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tugas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_create_rubrik_for_task()
    {
        $teacher = User::factory()->create(['roles_id' => 3]);
        $tugas = Tugas::factory()->create();
        
        $this->actingAs($teacher);

        $response = $this->post('/rubrik/store', [
            'tugas_id' => $tugas->id,
            'aspek' => ['Test Aspek'],
            'bobot' => [100]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rubrik_penilaian', [
            'tugas_id' => $tugas->id
        ]);
    }
}
```

### Test Data Factories

#### Factory Example
```php
<?php

namespace Database\Factories;

use App\Models\RubrikPenilaian;
use Illuminate\Database\Eloquent\Factories\Factory;

class RubrikPenilaianFactory extends Factory
{
    protected $model = RubrikPenilaian::class;

    public function definition()
    {
        return [
            'tugas_id' => Tugas::factory(),
            'aspek' => $this->faker->randomElement([
                'Isi & Analisis',
                'Struktur & Organisasi',
                'Bahasa & Ejaan'
            ]),
            'bobot' => $this->faker->numberBetween(10, 50),
            'deskripsi' => $this->faker->sentence(10),
        ];
    }
}
```

---

## 🚀 Deployment Guide

### Production Environment

#### Server Requirements
- PHP 8.1+
- MySQL 8.0+
- Nginx/Apache
- Redis (recommended)
- SSL Certificate

#### Environment Configuration
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=grading_system_prod
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-email-password
```

#### Deployment Steps

##### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt-get install -y nodejs
```

##### 2. Application Deployment
```bash
# Clone repository
git clone https://github.com/your-repo/grading-system.git
cd grading-system

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

##### 3. Web Server Configuration

###### Nginx Configuration
```nginx
server {
    listen 80;
    listen 443 ssl;
    server_name your-domain.com;
    root /path/to/grading-system/public;

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

##### 4. Queue Worker Setup
```bash
# Create systemd service
sudo nano /etc/systemd/system/grading-system-worker.service
```

```ini
[Unit]
Description=Grading System Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/grading-system/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
WorkingDirectory=/path/to/grading-system

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start service
sudo systemctl enable grading-system-worker
sudo systemctl start grading-system-worker
```

##### 5. Cron Jobs
```bash
# Add to crontab
crontab -e

# Add these lines
* * * * * cd /path/to/grading-system && php artisan schedule:run >> /dev/null 2>&1
0 2 * * * cd /path/to/grading-system && php artisan backup:run >> /dev/null 2>&1
```

### Monitoring & Logging

#### Application Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Monitor logs
tail -f storage/logs/laravel.log
```

#### Performance Monitoring
```php
// Add to AppServiceProvider
public function boot()
{
    if (app()->environment('production')) {
        DB::listen(function ($query) {
            if ($query->time > 1000) { // Log slow queries
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'time' => $query->time
                ]);
            }
        });
    }
}
```

---

## 🤝 Contributing Guidelines

### Development Workflow

#### 1. Fork and Clone
```bash
# Fork repository on GitHub
git clone https://github.com/your-username/grading-system.git
cd grading-system

# Add upstream remote
git remote add upstream https://github.com/original-repo/grading-system.git
```

#### 2. Create Feature Branch
```bash
# Create and switch to feature branch
git checkout -b feature/new-feature

# Or for bug fixes
git checkout -b fix/bug-description
```

#### 3. Development
```bash
# Make changes
# Write tests
# Update documentation

# Run tests
php artisan test

# Check code style
./vendor/bin/phpcs --standard=PSR12 app/
```

#### 4. Commit Changes
```bash
# Add changes
git add .

# Commit with descriptive message
git commit -m "feat: add new grading feature

- Add rubrik validation
- Update UI components
- Add unit tests

Closes #123"
```

#### 5. Push and Create PR
```bash
# Push to your fork
git push origin feature/new-feature

# Create Pull Request on GitHub
```

### Code Standards

#### PHP Code Style
- Follow PSR-12 coding standard
- Use meaningful variable and method names
- Add PHPDoc comments for public methods
- Keep methods under 20 lines when possible

#### JavaScript Code Style
- Use ES6+ features
- Follow Airbnb JavaScript Style Guide
- Use meaningful variable names
- Add JSDoc comments for functions

#### Database Standards
- Use snake_case for table and column names
- Add indexes for foreign keys
- Use proper data types
- Add constraints where appropriate

### Testing Requirements

#### Unit Tests
- Test all public methods
- Test edge cases and error conditions
- Achieve minimum 80% code coverage
- Use descriptive test names

#### Feature Tests
- Test complete user workflows
- Test authentication and authorization
- Test API endpoints
- Test error handling

### Documentation Requirements

#### Code Documentation
- Add PHPDoc for all public methods
- Document complex business logic
- Add inline comments for non-obvious code
- Update README for new features

#### API Documentation
- Document all new endpoints
- Include request/response examples
- Document error codes
- Update API changelog

### Pull Request Process

#### PR Requirements
- [ ] All tests pass
- [ ] Code follows style guidelines
- [ ] Documentation updated
- [ ] No breaking changes (or clearly documented)
- [ ] Screenshots for UI changes

#### Review Process
1. **Automated Checks** - CI/CD pipeline runs tests
2. **Code Review** - At least 2 reviewers required
3. **Testing** - Manual testing on staging environment
4. **Approval** - Maintainer approval required
5. **Merge** - Squash and merge to main branch

### Issue Reporting

#### Bug Reports
```markdown
**Bug Description**
Brief description of the bug

**Steps to Reproduce**
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected Behavior**
What you expected to happen

**Actual Behavior**
What actually happened

**Environment**
- OS: [e.g. Windows 10]
- Browser: [e.g. Chrome 91]
- PHP Version: [e.g. 8.1]
- Laravel Version: [e.g. 10.x]

**Screenshots**
If applicable, add screenshots

**Additional Context**
Any other context about the problem
```

#### Feature Requests
```markdown
**Feature Description**
Brief description of the feature

**Use Case**
Why is this feature needed?

**Proposed Solution**
How should this feature work?

**Alternatives Considered**
Any alternative solutions?

**Additional Context**
Any other context or screenshots
```

---

## 📚 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [MySQL Documentation](https://dev.mysql.com/doc/)

### Tools
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel Telescope](https://laravel.com/docs/telescope)
- [PHPStorm](https://www.jetbrains.com/phpstorm/)
- [Postman](https://www.postman.com/)

### Community
- [Laravel Discord](https://discord.gg/laravel)
- [Laravel News](https://laravel-news.com/)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)

---

**🛠️ Happy Coding! This guide will be updated as the system evolves.**
