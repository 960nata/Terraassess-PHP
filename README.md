# 🎓 Terra Assessment - Learning Management System

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Terra Assessment adalah Learning Management System (LMS) modern yang dibangun dengan Laravel 10. Aplikasi ini menyediakan platform lengkap untuk manajemen pembelajaran online dengan fitur tugas, ujian, materi, IoT integration, dan dashboard interaktif.

## 📚 Documentation

**📖 [Complete Documentation](PROJECT_DOCUMENTATION.md)** - Comprehensive guide covering all aspects of the system

**🚀 [Quick Start](#quick-start)** - Get up and running in minutes

## ✨ Key Features

### 🎯 **Core Features**
- **Multi-Role System** - Super Admin, Admin, Teacher, Student
- **Task Management** - Individual, Group, Multiple Choice, Essay
- **Exam System** - Real-time exams with timer and auto-grading
- **Material Management** - File upload and multimedia content
- **IoT Integration** - NPK sensor monitoring and data visualization
- **Real-time Notifications** - Live updates and alerts

### 🚀 **Advanced Features**
- **Rich Text Editor** - Modern Quill editor with formatting
- **File Management** - Secure file upload and storage
- **Data Analytics** - Charts and performance reports
- **Import/Export** - Excel-based bulk operations
- **Responsive Design** - Mobile-first approach
- **API Integration** - RESTful API with authentication

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js 16+

### Installation

1. **Clone Repository**
```bash
git clone <repository-url>
cd terraassess-php
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Start Server**
```bash
php artisan serve
```

### Default Accounts
- **Super Admin**: superadmin@example.com / password
- **Admin**: admin@example.com / password
- **Teacher**: teacher@example.com / password
- **Student**: student@example.com / password

## 🛠️ Tech Stack

### **Backend**
- **Laravel 10** - PHP Framework
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Excel** - Import/Export functionality

### **Frontend**
- **Blade Templates** - Server-side rendering
- **Bootstrap 5** - CSS Framework
- **Chart.js** - Data visualization
- **Quill.js** - Rich text editor

### **IoT Integration**
- **ESP8266/ESP32** - Microcontroller
- **NPK Sensor** - Soil nutrient monitoring
- **ThingsBoard** - IoT platform
- **Real-time Data** - Live sensor data visualization

## 📖 Documentation

For complete documentation including:
- **System Architecture** - Detailed system design
- **API Documentation** - Complete API reference
- **IoT Setup** - Hardware and software configuration
- **Deployment Guide** - Production deployment instructions
- **Troubleshooting** - Common issues and solutions

👉 **[Read Full Documentation](PROJECT_DOCUMENTATION.md)**

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Framework** - The PHP framework for web artisans
- **Bootstrap** - CSS framework for responsive design
- **Chart.js** - JavaScript charting library
- **Quill.js** - Rich text editor
- **ThingsBoard** - IoT platform for data collection