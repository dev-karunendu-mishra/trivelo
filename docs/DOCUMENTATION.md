# Trivelo - Hotel Booking System Documentation

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Database Schema](#database-schema)
4. [User Roles & Permissions](#user-roles--permissions)
5. [API Endpoints](#api-endpoints)
6. [Features by User Role](#features-by-user-role)
7. [Development Setup](#development-setup)
8. [Deployment Guide](#deployment-guide)
9. [Testing Strategy](#testing-strategy)
10. [Security Considerations](#security-considerations)

---

## 📝 Project Overview

**Trivelo** is a comprehensive hotel booking platform built with Laravel 12, designed to serve three distinct user types:

- **Super Admin**: System oversight, hotel approvals, analytics
- **Hotel Manager**: Hotel operations, room management, bookings
- **Customer**: Search, book, and manage hotel reservations

### Key Technologies
- **Framework**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze + Spatie Permission
- **Payment**: Stripe/PayPal Integration
- **Testing**: Pest PHP Testing Framework

---

## 🏗️ System Architecture

### MVC Architecture Pattern
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Models      │    │   Controllers   │    │      Views      │
│                 │    │                 │    │                 │
│ - User          │    │ - AuthController│    │ - Blade Templates│
│ - Hotel         │◄───┤ - HotelController│───►│ - Layouts       │
│ - Room          │    │ - BookingController   │ - Components    │
│ - Booking       │    │ - AdminController│    │ - Pages         │
│ - Payment       │    │ - APIController │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Service Layer Pattern
- **HotelService**: Hotel management logic
- **BookingService**: Reservation processing
- **PaymentService**: Transaction handling
- **NotificationService**: Email/SMS communications

---

## 🗄️ Database Schema

### Core Tables Structure

#### Users Table
```sql
users:
  - id (bigint, primary)
  - name (varchar)
  - email (varchar, unique)
  - email_verified_at (timestamp)
  - password (varchar)
  - phone (varchar)
  - avatar (varchar)
  - status (enum: active, inactive, suspended)
  - created_at (timestamp)
  - updated_at (timestamp)
```

#### Hotels Table
```sql
hotels:
  - id (bigint, primary)
  - user_id (bigint, foreign -> users.id)
  - name (varchar)
  - description (text)
  - address (text)
  - city (varchar)
  - state (varchar)
  - country (varchar)
  - postal_code (varchar)
  - phone (varchar)
  - email (varchar)
  - website (varchar)
  - star_rating (tinyint, 1-5)
  - status (enum: pending, approved, rejected, suspended)
  - latitude (decimal)
  - longitude (decimal)
  - check_in_time (time)
  - check_out_time (time)
  - created_at (timestamp)
  - updated_at (timestamp)
```

#### Rooms Table
```sql
rooms:
  - id (bigint, primary)
  - hotel_id (bigint, foreign -> hotels.id)
  - name (varchar)
  - description (text)
  - type (enum: single, double, suite, deluxe)
  - capacity (tinyint)
  - base_price (decimal)
  - area (decimal) -- square meters
  - bed_type (varchar)
  - quantity (integer) -- number of rooms available
  - status (enum: active, inactive, maintenance)
  - created_at (timestamp)
  - updated_at (timestamp)
```

#### Bookings Table
```sql
bookings:
  - id (bigint, primary)
  - user_id (bigint, foreign -> users.id)
  - hotel_id (bigint, foreign -> hotels.id)
  - room_id (bigint, foreign -> rooms.id)
  - booking_number (varchar, unique)
  - check_in_date (date)
  - check_out_date (date)
  - adults (tinyint)
  - children (tinyint)
  - total_amount (decimal)
  - status (enum: pending, confirmed, cancelled, completed, no_show)
  - special_requests (text)
  - created_at (timestamp)
  - updated_at (timestamp)
```

#### Payments Table
```sql
payments:
  - id (bigint, primary)
  - booking_id (bigint, foreign -> bookings.id)
  - payment_method (enum: stripe, paypal, bank_transfer)
  - transaction_id (varchar)
  - amount (decimal)
  - currency (varchar, default: USD)
  - status (enum: pending, completed, failed, refunded)
  - payment_date (timestamp)
  - created_at (timestamp)
  - updated_at (timestamp)
```

#### Additional Tables
```sql
amenities:
  - id (bigint, primary)
  - name (varchar)
  - icon (varchar)
  - type (enum: hotel, room)

hotel_amenities:
  - hotel_id (bigint, foreign)
  - amenity_id (bigint, foreign)

room_amenities:
  - room_id (bigint, foreign)
  - amenity_id (bigint, foreign)

hotel_images:
  - id (bigint, primary)
  - hotel_id (bigint, foreign)
  - room_id (bigint, foreign, nullable)
  - image_path (varchar)
  - alt_text (varchar)
  - is_primary (boolean)

reviews:
  - id (bigint, primary)
  - booking_id (bigint, foreign)
  - user_id (bigint, foreign)
  - hotel_id (bigint, foreign)
  - rating (tinyint, 1-5)
  - comment (text)
  - status (enum: pending, approved, rejected)
  - created_at (timestamp)
```

---

## 👥 User Roles & Permissions

### Role Hierarchy
```
Super Admin
├── Hotel Management
│   ├── Approve/Reject Hotels
│   ├── View All Hotels
│   └── Suspend Hotels
├── User Management
│   ├── View All Users
│   ├── Suspend Users
│   └── Manage Roles
├── System Analytics
│   ├── Revenue Reports
│   ├── Booking Statistics
│   └── Performance Metrics
└── System Configuration

Hotel Manager
├── Hotel Profile
│   ├── Update Hotel Info
│   ├── Manage Images
│   └── Set Policies
├── Room Management
│   ├── Add/Edit Rooms
│   ├── Set Pricing
│   └── Manage Availability
├── Booking Management
│   ├── View Bookings
│   ├── Confirm Reservations
│   └── Handle Cancellations
└── Analytics
    ├── Revenue Reports
    ├── Occupancy Rates
    └── Guest Reviews

Customer
├── Hotel Search
│   ├── Filter by Location
│   ├── Filter by Price
│   └── Filter by Amenities
├── Booking Management
│   ├── Make Reservations
│   ├── View Booking History
│   └── Cancel Bookings
├── Reviews & Ratings
│   ├── Leave Reviews
│   └── Rate Hotels
└── Profile Management
    ├── Update Personal Info
    ├── Manage Payment Methods
    └── View Booking History
```

---

## 🔌 API Endpoints

### Authentication Endpoints
```
POST   /api/register        - User registration
POST   /api/login          - User login
POST   /api/logout         - User logout
POST   /api/refresh        - Refresh token
POST   /api/forgot-password - Password reset request
POST   /api/reset-password  - Password reset
```

### Hotel Endpoints
```
GET    /api/hotels         - List hotels (public)
GET    /api/hotels/{id}    - Get hotel details (public)
POST   /api/hotels         - Create hotel (hotel manager)
PUT    /api/hotels/{id}    - Update hotel (hotel manager)
DELETE /api/hotels/{id}    - Delete hotel (admin)
GET    /api/hotels/{id}/rooms - Get hotel rooms (public)
```

### Room Endpoints
```
GET    /api/rooms          - List rooms with filters
GET    /api/rooms/{id}     - Get room details
POST   /api/rooms          - Create room (hotel manager)
PUT    /api/rooms/{id}     - Update room (hotel manager)
DELETE /api/rooms/{id}     - Delete room (hotel manager)
GET    /api/rooms/{id}/availability - Check availability
```

### Booking Endpoints
```
GET    /api/bookings       - List user bookings
GET    /api/bookings/{id}  - Get booking details
POST   /api/bookings       - Create booking
PUT    /api/bookings/{id}  - Update booking
DELETE /api/bookings/{id}  - Cancel booking
POST   /api/bookings/{id}/confirm - Confirm booking (hotel)
```

### Payment Endpoints
```
POST   /api/payments       - Process payment
GET    /api/payments/{id}  - Get payment details
POST   /api/payments/{id}/refund - Process refund
```

### Admin Endpoints
```
GET    /api/admin/hotels   - List all hotels
PUT    /api/admin/hotels/{id}/approve - Approve hotel
PUT    /api/admin/hotels/{id}/reject  - Reject hotel
GET    /api/admin/users    - List all users
GET    /api/admin/analytics - System analytics
GET    /api/admin/reports  - Generate reports
```

---

## 🎯 Features by User Role

### Super Admin Features
- **Dashboard**: System overview, key metrics, alerts
- **Hotel Management**: Approve/reject hotel registrations
- **User Management**: View, suspend, manage all users
- **Analytics**: Revenue reports, booking trends, performance metrics
- **System Settings**: Configure app settings, commission rates
- **Content Management**: Manage amenities, locations

### Hotel Manager Features
- **Hotel Dashboard**: Booking overview, revenue, occupancy
- **Profile Management**: Update hotel information, images, policies
- **Room Management**: Add/edit rooms, set pricing, availability
- **Booking Management**: View, confirm, modify reservations
- **Guest Communication**: Respond to reviews, send notifications
- **Reports**: Revenue analytics, occupancy reports

### Customer Features
- **Hotel Search**: Advanced filtering, map view, sorting
- **Booking Process**: Select dates, rooms, guest details, payment
- **Account Management**: Profile, booking history, preferences
- **Reviews & Ratings**: Leave feedback for completed stays
- **Wishlist**: Save favorite hotels
- **Notifications**: Booking confirmations, reminders

---

## 🚀 Development Setup

### Prerequisites
- PHP 8.2+
- Node.js 18+
- MySQL 8.0+ or PostgreSQL 13+
- Composer
- Git

### Installation Steps
```bash
# Clone the repository
git clone [repository-url] trivelo
cd trivelo

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### Environment Configuration
```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trivelo
DB_USERNAME=root
DB_PASSWORD=

# Payment Gateways
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

# File Storage
FILESYSTEM_DISK=local
# For production use S3:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=
# AWS_SECRET_ACCESS_KEY=
# AWS_DEFAULT_REGION=
# AWS_BUCKET=
```

---

## 📦 Deployment Guide

### Production Requirements
- Web server (Apache/Nginx)
- PHP 8.2+ with required extensions
- MySQL/PostgreSQL database
- Redis (for caching and queues)
- SSL certificate

### Deployment Checklist
- [ ] Environment variables configured
- [ ] Database migrated and seeded
- [ ] File permissions set correctly
- [ ] Queue workers configured
- [ ] SSL certificate installed
- [ ] CDN configured for assets
- [ ] Monitoring tools setup

### Optimization
```bash
# Production optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🧪 Testing Strategy

### Test Structure
```
tests/
├── Feature/
│   ├── Auth/
│   ├── Hotel/
│   ├── Booking/
│   ├── Payment/
│   └── Admin/
├── Unit/
│   ├── Models/
│   ├── Services/
│   └── Helpers/
└── Integration/
    ├── API/
    └── Database/
```

### Testing Commands
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/BookingTest.php
```

---

## 🔒 Security Considerations

### Authentication & Authorization
- JWT tokens for API authentication
- Role-based access control (RBAC)
- Password hashing with bcrypt
- Rate limiting on sensitive endpoints

### Data Protection
- Input validation and sanitization
- CSRF protection
- XSS prevention
- SQL injection prevention through Eloquent ORM

### Payment Security
- PCI DSS compliance for payment processing
- Secure token storage
- Encrypted sensitive data
- Audit trails for transactions

### General Security
- HTTPS enforced in production
- Security headers configured
- Regular dependency updates
- Error handling without information disclosure

---

## 📊 Performance Considerations

### Database Optimization
- Proper indexing on foreign keys
- Query optimization
- Database connection pooling
- Caching for frequently accessed data

### Application Performance
- Redis caching for sessions and data
- Queue processing for heavy tasks
- Image optimization and CDN
- Lazy loading for large datasets

### Monitoring
- Application performance monitoring
- Error tracking
- Server resource monitoring
- Database query analysis

---

## 🔄 Future Enhancements

### Phase 2 Features
- Mobile application (Flutter/React Native)
- Multi-language support
- Advanced analytics dashboard
- Integration with third-party booking platforms

### Phase 3 Features
- AI-powered recommendations
- Dynamic pricing algorithms
- Real-time chat support
- Advanced reporting tools

---

*Last Updated: September 14, 2025*
*Version: 1.0.0*