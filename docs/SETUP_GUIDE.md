# Development Setup Guide - Trivelo Hotel Booking System

## 📋 Prerequisites

Before setting up the project, ensure you have the following installed on your system:

### Required Software
- **PHP**: 8.2 or higher with extensions:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD (for image processing)
- **Composer**: Latest version for PHP dependency management
- **Node.js**: 18.x or higher for frontend asset compilation
- **npm**: Comes with Node.js for JavaScript package management
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Redis**: For caching and session storage (optional but recommended)
- **Git**: For version control

### Optional Tools
- **Laravel Valet/Herd**: For local development environment (macOS/Windows)
- **Docker**: Alternative development environment
- **Postman/Insomnia**: For API testing

---

## 🚀 Installation Steps

### 1. Clone the Repository
```bash
# Clone the repository
git clone https://github.com/your-username/trivelo.git
cd trivelo
```

### 2. Install PHP Dependencies
```bash
# Install Composer dependencies
composer install

# If you encounter memory issues
php -d memory_limit=-1 /usr/local/bin/composer install
```

### 3. Install Node.js Dependencies
```bash
# Install npm dependencies
npm install

# Alternative using Yarn
yarn install
```

### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Environment Variables
Edit the `.env` file with your specific settings:

```env
# Application
APP_NAME=Trivelo
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trivelo
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration (Optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@trivelo.com"
MAIL_FROM_NAME="${APP_NAME}"

# File Storage
FILESYSTEM_DISK=local

# Payment Gateway (Development)
STRIPE_KEY=pk_test_your_stripe_publishable_key
STRIPE_SECRET=sk_test_your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox

# Google Maps (Optional)
GOOGLE_MAPS_API_KEY=your_google_maps_api_key

# Social Login (Optional)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret

FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
```

### 6. Database Setup
```bash
# Create database (MySQL example)
mysql -u root -p
mysql> CREATE DATABASE trivelo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
mysql> exit

# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Or run migrations and seeding together
php artisan migrate:fresh --seed
```

### 7. Install Additional Packages
```bash
# Install required Laravel packages
composer require spatie/laravel-permission
composer require stripe/stripe-php
composer require intervention/image
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel

# Publish package configurations
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 8. Storage Setup
```bash
# Create symbolic link for public storage
php artisan storage:link

# Create required directories
mkdir -p storage/app/public/hotels
mkdir -p storage/app/public/rooms
mkdir -p storage/app/public/avatars
mkdir -p storage/app/public/documents
```

### 9. Compile Assets
```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes during development
npm run dev -- --watch
```

---

## 🏃‍♂️ Running the Application

### Start Development Server
```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

### Queue Worker (Background Jobs)
```bash
# Start queue worker for background processing
php artisan queue:work

# Or use Laravel Horizon (recommended for production)
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

### Schedule Runner (Cron Jobs)
```bash
# Add to your crontab for scheduled tasks
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

# Or run manually during development
php artisan schedule:work
```

---

## 🗄️ Database Seeding

### Default Seeders
The project includes several seeders to populate your database with sample data:

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=HotelSeeder
php artisan db:seed --class=AmenitySeeder
```

### Sample Users Created
After seeding, you'll have these test accounts:

**Super Admin:**
- Email: `admin@trivelo.com`
- Password: `password`

**Hotel Manager:**
- Email: `hotel@example.com`
- Password: `password`

**Customer:**
- Email: `customer@example.com`
- Password: `password`

---

## 🧪 Testing

### Setup Test Environment
```bash
# Create test database
mysql> CREATE DATABASE trivelo_testing;

# Copy test environment file
cp .env .env.testing

# Update test database settings in .env.testing
DB_DATABASE=trivelo_testing
```

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage report
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/BookingTest.php

# Run tests in parallel (if you have pestphp/pest-plugin-parallel)
php artisan test --parallel
```

---

## 🐳 Docker Setup (Alternative)

### Docker Compose Configuration
Create `docker-compose.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    environment:
      - DB_HOST=mysql
      - REDIS_HOST=redis
    depends_on:
      - mysql
      - redis

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: trivelo
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"

  redis:
    image: redis:7.0
    ports:
      - "6379:6379"

  mailpit:
    image: axllent/mailpit
    ports:
      - "1025:1025"
      - "8025:8025"

volumes:
  mysql_data:
```

### Run with Docker
```bash
# Build and start containers
docker-compose up -d

# Run Laravel commands inside container
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

---

## 🔧 Development Tools

### Useful Laravel Commands
```bash
# Clear all caches
php artisan optimize:clear

# Generate IDE helper files (if using barryvdh/laravel-ide-helper)
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
php artisan ide-helper:models

# Create new controller
php artisan make:controller HotelController --resource

# Create new model with migration
php artisan make:model Hotel -m

# Create new seeder
php artisan make:seeder HotelSeeder

# Create new job
php artisan make:job ProcessBooking

# Create new request validation
php artisan make:request StoreHotelRequest
```

### Database Tools
```bash
# View database status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Reset all migrations and reseed
php artisan migrate:fresh --seed

# Generate migration from existing database
php artisan migrate:generate

# View database schema
php artisan schema:dump
```

---

## 🔍 Debugging

### Enable Debug Mode
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Laravel Telescope (Development)
```bash
# Install Telescope for debugging
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# Access at http://localhost:8000/telescope
```

### Error Logging
```bash
# View recent logs
tail -f storage/logs/laravel.log

# View error logs
php artisan log:clear
```

---

## 🔒 Security Setup

### File Permissions
```bash
# Set correct permissions for Laravel
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# For development (less secure but functional)
chmod -R 777 storage bootstrap/cache
```

### Security Headers
Add to `public/.htaccess`:
```apache
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
</IfModule>
```

---

## 📊 Performance Optimization

### Caching Configuration
```bash
# Enable various caches for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear all caches
php artisan optimize:clear
```

### Database Optimization
```bash
# Index commonly queried columns
php artisan make:migration add_indexes_to_tables
```

---

## 🚀 Going to Production

### Pre-deployment Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up Redis for sessions and cache
- [ ] Configure mail settings
- [ ] Set up SSL certificate
- [ ] Configure file storage (S3, etc.)
- [ ] Set up monitoring tools
- [ ] Configure backup strategy

### Deployment Commands
```bash
# Pull latest code
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and cache configurations
php artisan optimize

# Restart queue workers
php artisan queue:restart
```

---

## 🆘 Troubleshooting

### Common Issues

**1. Permission Errors**
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

**2. Composer Memory Issues**
```bash
php -d memory_limit=-1 $(which composer) install
```

**3. Asset Compilation Errors**
```bash
# Clear npm cache and reinstall
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

**4. Database Connection Issues**
- Check database credentials in `.env`
- Ensure database server is running
- Verify PHP MySQL/PDO extension is installed

**5. Queue Not Processing**
```bash
# Restart queue worker
php artisan queue:restart
php artisan queue:work
```

### Debug Commands
```bash
# Check system requirements
php artisan about

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check environment configuration
php artisan config:show

# View routes
php artisan route:list
```

---

## 📖 Additional Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Permission Docs](https://spatie.be/docs/laravel-permission)
- [Stripe PHP Documentation](https://stripe.com/docs/api/php)

### Tools
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper)
- [PHP CS Fixer](https://cs.symfony.com/)
- [PHPStan](https://phpstan.org/)

---

*Last Updated: September 14, 2025*