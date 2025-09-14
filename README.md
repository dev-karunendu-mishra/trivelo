# Trivelo - Hotel Booking System

<div align="center">

![Trivelo Logo](https://via.placeholder.com/200x80/3B82F6/FFFFFF?text=TRIVELO)

**A comprehensive hotel booking platform built with Laravel 12**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

[Features](#-features) • [Installation](#-installation) • [Documentation](#-documentation) • [API](#-api-reference) • [Contributing](#-contributing)

</div>

---

## 📋 About Trivelo

Trivelo is a modern, feature-rich hotel booking system designed to serve three distinct user types:

- **🏢 Super Admin**: System oversight, hotel approvals, and analytics
- **🏨 Hotel Managers**: Hotel operations, room management, and bookings
- **👥 Customers**: Search, book, and manage hotel reservations

Built with Laravel 12 and modern web technologies, Trivelo provides a scalable solution for hotel booking operations with comprehensive features for all stakeholders.

---

## ✨ Key Features

### 🔐 Authentication & Authorization
- Multi-role authentication (Super Admin, Hotel Manager, Customer)
- Role-based permissions with Spatie Permission
- JWT token-based API authentication
- Social login integration (Google, Facebook)

### 🏨 Hotel Management
- Hotel registration and approval workflow
- Comprehensive hotel profiles with images
- Room management with pricing and availability
- Amenities and facilities management

### 📅 Booking System
- Advanced search with filters (location, price, dates, amenities)
- Real-time availability checking
- Secure booking process with guest details
- Booking status management (pending, confirmed, cancelled)

### 💳 Payment Processing
- Multiple payment gateways (Stripe, PayPal)
- Secure payment processing
- Refund management
- Transaction history and receipts

### ⭐ Reviews & Ratings
- Customer review system
- Multi-criteria ratings (cleanliness, service, location, value)
- Hotel response to reviews
- Review moderation system

### 📊 Analytics & Reports
- Revenue analytics and trends
- Booking statistics
- Occupancy reports
- Performance metrics dashboard

### 🔧 Additional Features
- Responsive design with Tailwind CSS
- Email notifications and confirmations
- File upload and image management
- Advanced search and filtering
- API-first architecture
- Comprehensive admin panel

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Caching**: Redis
- **Payment**: Stripe, PayPal
- **Storage**: Local / Amazon S3
- **Testing**: Pest PHP
- **Queue**: Redis / Database

---

## 🚀 Quick Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+ or PostgreSQL 13+
- Redis (optional)

### Installation Steps
```bash
# Clone the repository
git clone https://github.com/your-username/trivelo.git
cd trivelo

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

Visit `http://localhost:8000` to access the application.

**Default Login Credentials:**
- Admin: `admin@trivelo.com` / `password`
- Hotel Manager: `hotel@example.com` / `password`
- Customer: `customer@example.com` / `password`

For detailed setup instructions, see the [Setup Guide](docs/SETUP_GUIDE.md).

---

## 📚 Documentation

- **[Complete Documentation](docs/DOCUMENTATION.md)** - Comprehensive project overview
- **[Database Schema](docs/DATABASE_SCHEMA.md)** - Detailed database structure
- **[API Documentation](docs/API_DOCUMENTATION.md)** - RESTful API reference
- **[Swagger Documentation](docs/SWAGGER_GUIDE.md)** - Interactive API documentation
- **[Setup Guide](docs/SETUP_GUIDE.md)** - Development environment setup
- **[User Manual](docs/USER_MANUAL.md)** - End-user guide for all roles

---

## 🔌 API Reference

Trivelo provides a comprehensive RESTful API for all operations:

### Base URL
- Development: `http://localhost:8000/api`
- Production: `https://your-domain.com/api`
- **Interactive Docs**: `http://localhost:8000/api/documentation` (Swagger UI)

### Key Endpoints
- `POST /auth/login` - User authentication
- `GET /hotels` - List hotels with filters
- `POST /bookings` - Create new booking
- `POST /payments` - Process payment
- `GET /admin/analytics` - System analytics

For complete API documentation with examples, see [docs/API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md).

---

## 🗄️ Database Schema

The application uses a well-structured database schema with the following core tables:

- **users** - System users with roles
- **hotels** - Hotel information
- **rooms** - Room types and details
- **bookings** - Reservation records
- **payments** - Transaction history
- **reviews** - Customer feedback

For detailed schema information, see [docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md).

---

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 🚀 Deployment

### Production Requirements
- Web server (Apache/Nginx)
- PHP 8.2+ with required extensions
- MySQL/PostgreSQL database
- Redis for caching and queues
- SSL certificate

### Quick Deployment
```bash
# Optimize for production
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start queue workers
php artisan queue:work --daemon
```

---

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for details.

### Development Workflow
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes and add tests
4. Commit your changes: `git commit -m 'Add amazing feature'`
5. Push to the branch: `git push origin feature/amazing-feature`
6. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🆘 Support

- **Documentation**: Check our comprehensive docs above
- **Issues**: [GitHub Issues](https://github.com/your-username/trivelo/issues)
- **Email**: support@trivelo.com
- **Discord**: [Join our community](https://discord.gg/trivelo)

---

## 🌟 Roadmap

- [ ] Mobile application (Flutter/React Native)
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Third-party booking platform integration
- [ ] AI-powered recommendations
- [ ] Real-time chat support

---

## 📊 Project Stats

<div align="center">

![GitHub stars](https://img.shields.io/github/stars/your-username/trivelo?style=social)
![GitHub forks](https://img.shields.io/github/forks/your-username/trivelo?style=social)
![GitHub issues](https://img.shields.io/github/issues/your-username/trivelo)
![GitHub pull requests](https://img.shields.io/github/issues-pr/your-username/trivelo)

</div>

---

<div align="center">

**Built with ❤️ using Laravel**

[⬆ Back to Top](#trivelo---hotel-booking-system)

</div>
