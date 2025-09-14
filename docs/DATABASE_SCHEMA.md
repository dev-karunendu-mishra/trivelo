# Database Schema Documentation

## Entity Relationship Diagram (ERD)

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    Users    │       │   Hotels    │       │    Rooms    │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │◄─────┤│ user_id (FK)│       │ hotel_id(FK)│
│ name        │       │ id (PK)     │◄─────┤│ id (PK)     │
│ email       │       │ name        │       │ name        │
│ password    │       │ address     │       │ type        │
│ phone       │       │ status      │       │ price       │
│ status      │       │ rating      │       │ capacity    │
└─────────────┘       └─────────────┘       └─────────────┘
       │                       │                       │
       │              ┌─────────────┐                  │
       │              │  Bookings   │                  │
       │              ├─────────────┤                  │
       └─────────────►│ user_id (FK)│◄─────────────────┘
                      │ hotel_id(FK)│
                      │ room_id (FK)│
                      │ id (PK)     │
                      │ check_in    │
                      │ check_out   │
                      │ status      │
                      └─────────────┘
                              │
                      ┌─────────────┐
                      │  Payments   │
                      ├─────────────┤
                      │booking_id(FK)│
                      │ id (PK)     │
                      │ amount      │
                      │ method      │
                      │ status      │
                      └─────────────┘
```

## Table Definitions

### 1. Users Table
Stores all system users with different roles.

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    avatar VARCHAR(500) NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);
```

**Relationships:**
- One-to-Many with Hotels (as hotel manager)
- One-to-Many with Bookings (as customer)
- One-to-Many with Reviews
- Many-to-Many with Roles (through model_has_roles)

---

### 2. Hotels Table
Contains hotel information and settings.

```sql
CREATE TABLE hotels (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20),
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    website VARCHAR(500) NULL,
    star_rating TINYINT UNSIGNED CHECK (star_rating BETWEEN 1 AND 5),
    status ENUM('pending', 'approved', 'rejected', 'suspended') DEFAULT 'pending',
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    check_in_time TIME DEFAULT '14:00:00',
    check_out_time TIME DEFAULT '11:00:00',
    policies TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_city (city),
    INDEX idx_country (country),
    INDEX idx_star_rating (star_rating),
    INDEX idx_slug (slug)
);
```

**Relationships:**
- Belongs-to User (hotel manager)
- One-to-Many with Rooms
- One-to-Many with Bookings
- One-to-Many with Reviews
- One-to-Many with HotelImages
- Many-to-Many with Amenities

---

### 3. Rooms Table
Room types and details for each hotel.

```sql
CREATE TABLE rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('single', 'double', 'twin', 'suite', 'deluxe', 'family') NOT NULL,
    capacity TINYINT UNSIGNED NOT NULL DEFAULT 2,
    base_price DECIMAL(10, 2) NOT NULL,
    area DECIMAL(6, 2) NULL COMMENT 'Area in square meters',
    bed_type VARCHAR(100) NULL,
    quantity INTEGER UNSIGNED NOT NULL DEFAULT 1,
    status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    INDEX idx_hotel_id (hotel_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_capacity (capacity),
    INDEX idx_base_price (base_price),
    UNIQUE KEY unique_hotel_room_slug (hotel_id, slug)
);
```

**Relationships:**
- Belongs-to Hotel
- One-to-Many with Bookings
- One-to-Many with RoomImages
- Many-to-Many with Amenities

---

### 4. Bookings Table
Customer reservations and booking details.

```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    hotel_id BIGINT UNSIGNED NOT NULL,
    room_id BIGINT UNSIGNED NOT NULL,
    booking_number VARCHAR(50) UNIQUE NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    adults TINYINT UNSIGNED NOT NULL DEFAULT 1,
    children TINYINT UNSIGNED NOT NULL DEFAULT 0,
    nights INTEGER UNSIGNED GENERATED ALWAYS AS (DATEDIFF(check_out_date, check_in_date)) STORED,
    room_price DECIMAL(10, 2) NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    tax_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'no_show') DEFAULT 'pending',
    special_requests TEXT NULL,
    guest_name VARCHAR(255) NOT NULL,
    guest_email VARCHAR(255) NOT NULL,
    guest_phone VARCHAR(20) NOT NULL,
    cancelled_at TIMESTAMP NULL,
    cancellation_reason TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_hotel_id (hotel_id),
    INDEX idx_room_id (room_id),
    INDEX idx_booking_number (booking_number),
    INDEX idx_status (status),
    INDEX idx_check_in_date (check_in_date),
    INDEX idx_check_out_date (check_out_date),
    INDEX idx_created_at (created_at)
);
```

**Relationships:**
- Belongs-to User (customer)
- Belongs-to Hotel
- Belongs-to Room
- One-to-Many with Payments
- One-to-One with Review

---

### 5. Payments Table
Payment transactions and history.

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    payment_method ENUM('stripe', 'paypal', 'bank_transfer', 'cash') NOT NULL,
    transaction_id VARCHAR(255) NULL,
    payment_intent_id VARCHAR(255) NULL,
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    status ENUM('pending', 'completed', 'failed', 'refunded', 'partially_refunded') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    refund_amount DECIMAL(10, 2) NULL,
    refund_date TIMESTAMP NULL,
    refund_reason TEXT NULL,
    gateway_response JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_status (status),
    INDEX idx_payment_date (payment_date)
);
```

**Relationships:**
- Belongs-to Booking

---

### 6. Reviews Table
Customer reviews and ratings.

```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    hotel_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255) NULL,
    comment TEXT NOT NULL,
    cleanliness_rating TINYINT UNSIGNED NULL CHECK (cleanliness_rating BETWEEN 1 AND 5),
    service_rating TINYINT UNSIGNED NULL CHECK (service_rating BETWEEN 1 AND 5),
    location_rating TINYINT UNSIGNED NULL CHECK (location_rating BETWEEN 1 AND 5),
    value_rating TINYINT UNSIGNED NULL CHECK (value_rating BETWEEN 1 AND 5),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_response TEXT NULL,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_user_id (user_id),
    INDEX idx_hotel_id (hotel_id),
    INDEX idx_rating (rating),
    INDEX idx_status (status),
    UNIQUE KEY unique_booking_review (booking_id)
);
```

**Relationships:**
- Belongs-to Booking
- Belongs-to User
- Belongs-to Hotel

---

### 7. Amenities Table
Available amenities for hotels and rooms.

```sql
CREATE TABLE amenities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    icon VARCHAR(100) NULL,
    type ENUM('hotel', 'room', 'both') DEFAULT 'both',
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_type (type),
    INDEX idx_is_active (is_active),
    INDEX idx_sort_order (sort_order)
);
```

**Sample Amenities Data:**
```sql
INSERT INTO amenities (name, slug, icon, type) VALUES
('WiFi', 'wifi', 'fas fa-wifi', 'both'),
('Swimming Pool', 'swimming-pool', 'fas fa-swimming-pool', 'hotel'),
('Gym', 'gym', 'fas fa-dumbbell', 'hotel'),
('Air Conditioning', 'air-conditioning', 'fas fa-snowflake', 'room'),
('TV', 'tv', 'fas fa-tv', 'room'),
('Mini Bar', 'mini-bar', 'fas fa-glass-martini', 'room'),
('Room Service', 'room-service', 'fas fa-concierge-bell', 'hotel'),
('Parking', 'parking', 'fas fa-parking', 'hotel');
```

---

### 8. Hotel Images Table
Image storage for hotels and rooms.

```sql
CREATE TABLE hotel_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id BIGINT UNSIGNED NOT NULL,
    room_id BIGINT UNSIGNED NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    INDEX idx_hotel_id (hotel_id),
    INDEX idx_room_id (room_id),
    INDEX idx_is_primary (is_primary),
    INDEX idx_sort_order (sort_order)
);
```

**Relationships:**
- Belongs-to Hotel
- Belongs-to Room (optional)

---

### 9. Pivot Tables

#### Hotel Amenities
```sql
CREATE TABLE hotel_amenities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hotel_id BIGINT UNSIGNED NOT NULL,
    amenity_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_hotel_amenity (hotel_id, amenity_id)
);
```

#### Room Amenities
```sql
CREATE TABLE room_amenities (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED NOT NULL,
    amenity_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE,
    UNIQUE KEY unique_room_amenity (room_id, amenity_id)
);
```

---

### 10. Additional Utility Tables

#### Room Availability (Optional - for complex availability tracking)
```sql
CREATE TABLE room_availability (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    available_quantity INTEGER UNSIGNED NOT NULL,
    price_override DECIMAL(10, 2) NULL,
    is_blocked BOOLEAN DEFAULT FALSE,
    block_reason VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    UNIQUE KEY unique_room_date (room_id, date),
    INDEX idx_date (date),
    INDEX idx_available_quantity (available_quantity)
);
```

#### Activity Log (for audit trail)
```sql
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    model_type VARCHAR(255) NULL,
    model_id BIGINT UNSIGNED NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_model_type_id (model_type, model_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);
```

---

## Database Indexes Strategy

### Primary Indexes (Already included above)
- All foreign keys are indexed
- Frequently queried columns have indexes
- Unique constraints where necessary

### Composite Indexes for Complex Queries
```sql
-- For hotel search queries
CREATE INDEX idx_hotels_location_status ON hotels(city, country, status);
CREATE INDEX idx_hotels_rating_status ON hotels(star_rating, status);

-- For booking queries
CREATE INDEX idx_bookings_dates_status ON bookings(check_in_date, check_out_date, status);
CREATE INDEX idx_bookings_hotel_dates ON bookings(hotel_id, check_in_date, check_out_date);

-- For room availability queries
CREATE INDEX idx_rooms_hotel_status ON rooms(hotel_id, status);
CREATE INDEX idx_rooms_type_capacity ON rooms(type, capacity);
```

---

## Data Migration Order

When setting up the database, create tables in this order to avoid foreign key constraint issues:

1. `users`
2. `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions` (Spatie Permission tables)
3. `amenities`
4. `hotels`
5. `hotel_amenities`
6. `hotel_images`
7. `rooms`
8. `room_amenities`
9. `bookings`
10. `payments`
11. `reviews`
12. `room_availability` (if used)
13. `activity_logs`

---

## Sample Data Relationships

```sql
-- User creates hotel
User(id: 1, role: hotel_manager) -> Hotel(id: 1, user_id: 1)

-- Hotel has multiple rooms
Hotel(id: 1) -> Room(id: 1, hotel_id: 1), Room(id: 2, hotel_id: 1)

-- Customer books room
User(id: 2, role: customer) -> Booking(user_id: 2, hotel_id: 1, room_id: 1)

-- Booking has payment
Booking(id: 1) -> Payment(booking_id: 1)

-- Booking gets reviewed
Booking(id: 1) -> Review(booking_id: 1, user_id: 2, hotel_id: 1)
```

This schema supports all the features required for the hotel booking system while maintaining data integrity and performance through proper indexing and relationships.