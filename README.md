# 🏪 Cashier Management System

A comprehensive, modern cashier management system built with Laravel 11, featuring real-time analytics, user management, sales tracking, and inventory management.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## ✨ Features

### 🎯 **Core Functionality**
- **User Management**: Complete CRUD operations with role-based access control
- **Sales Management**: Track sales, transactions, and customer data
- **Inventory Management**: Monitor product stock levels and low stock alerts
- **Dashboard Analytics**: Real-time statistics and interactive charts
- **Export Functionality**: CSV export for sales data and reports

### 🔐 **Security Features**
- **Authentication**: Secure login/logout with bcrypt password hashing
- **Authorization**: Role-based access control (Admin, Cashier, User)
- **CSRF Protection**: All forms protected against CSRF attacks
- **Input Validation**: Comprehensive server-side validation
- **Security Headers**: XSS, clickjacking, and MIME type sniffing protection
- **SQL Injection Prevention**: Parameterized queries and Eloquent ORM

### 🎨 **User Experience**
- **Modern UI/UX**: Clean, professional design with smooth animations
- **Responsive Design**: Mobile-first approach, works on all devices
- **Interactive Elements**: Hover effects, loading states, and transitions
- **Color-Coded Status**: Intuitive visual indicators for different states
- **Real-time Updates**: Live data refresh and notifications

### 📊 **Analytics & Reporting**
- **Live Dashboard**: Real-time sales statistics and key metrics
- **Growth Tracking**: Percentage changes and trend analysis
- **Interactive Charts**: Sales trends and payment method distribution
- **Smart Alerts**: Low stock notifications and pending sales alerts
- **Export Reports**: CSV export functionality for data analysis

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM (for frontend assets)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/cashier-management-system.git
   cd cashier-management-system
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cashier_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

### Default Login Credentials
- **Admin**: `admin` / `password`
- **Cashier**: `cashier` / `password`

## 🛠️ Technology Stack

### Backend
- **Laravel 11**: PHP framework
- **MySQL**: Database management
- **Eloquent ORM**: Database abstraction
- **Carbon**: Date/time manipulation
- **Bcrypt**: Password hashing

### Frontend
- **Blade Templates**: Server-side rendering
- **Bootstrap 5**: CSS framework
- **Font Awesome**: Icons
- **ApexCharts**: Interactive charts
- **JavaScript ES6**: Modern JavaScript features

### Security
- **CSRF Tokens**: Cross-site request forgery protection
- **Input Validation**: Server-side validation rules
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping
- **Security Headers**: HTTP security headers

## 📊 Database Schema

### Core Tables
- **`akun`**: User accounts and authentication
- **`roles`**: User roles and permissions
- **`permissions`**: System permissions
- **`role_permissions`**: Role-permission relationships
- **`produk`**: Product inventory
- **`penjualan`**: Sales transactions
- **`penjualan_detail`**: Sales line items
- **`metode_pembayaran`**: Payment methods

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="Cashier Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cashier_system
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set up SSL certificate
- [ ] Configure web server (Apache/Nginx)
- [ ] Set proper file permissions
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

## 📈 Performance

### Optimizations
- **Database Indexing**: Optimized queries with proper indexes
- **Caching**: 5-minute cache for dashboard statistics
- **Lazy Loading**: Charts load after page initialization
- **Pagination**: Large datasets paginated for better performance
- **Asset Optimization**: Minified CSS and JavaScript

## 🔒 Security

### Implemented Security Measures
- **Authentication**: Secure user authentication system
- **Authorization**: Role-based access control
- **CSRF Protection**: All forms protected
- **Input Validation**: Server-side validation
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping
- **Security Headers**: Comprehensive HTTP security headers

## 🧪 Testing

### Manual Testing
- ✅ User authentication and authorization
- ✅ CRUD operations for all modules
- ✅ Dashboard functionality
- ✅ Responsive design testing
- ✅ Error handling validation
- ✅ Security feature testing

## 📚 API Documentation

### Authentication Endpoints
```
POST /login          - User login
POST /logout         - User logout
GET  /user          - Get current user
```

### User Management Endpoints
```
GET    /users           - List all users
POST   /users           - Create new user
GET    /users/{id}      - Get user details
PUT    /users/{id}      - Update user
DELETE /users/{id}      - Delete user
```

### Sales Management Endpoints
```
GET  /api/sales/stats     - Get sales statistics
GET  /api/sales           - List sales with pagination
GET  /api/sales/{id}      - Get sale details
GET  /api/sales/export    - Export sales data
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Follow security best practices

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

## 🙏 Acknowledgments

- Laravel community for the excellent framework
- Bootstrap team for the responsive CSS framework
- Font Awesome for the beautiful icons
- ApexCharts for the interactive charts

## 📞 Support

For support, email support@yourcompany.com or create an issue in this repository.

---

**⭐ If you found this project helpful, please give it a star!**

*Built with ❤️ using Laravel 11 and modern web technologies*