<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AnalyticsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $admin = User::firstOrCreate(
            ['email' => 'admin@trivelo.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin'
            ]
        );

        $hotelManager = User::firstOrCreate(
            ['email' => 'manager@trivelo.com'],
            [
                'name' => 'Hotel Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'hotel_manager'
            ]
        );

        // Create test customers
        $customers = [];
        for ($i = 1; $i <= 20; $i++) {
            $customers[] = User::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'name' => "Customer {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'customer'
                ]
            );
        }

        // Create test hotel
        $hotel = Hotel::firstOrCreate(
            ['name' => 'Grand Trivelo Hotel'],
            [
                'user_id' => $hotelManager->id,
                'slug' => 'grand-trivelo-hotel',
                'description' => 'A luxury hotel with world-class amenities and service.',
                'address' => '123 Main Street, City Center',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'phone' => '+1-555-123-4567',
                'email' => 'info@grandtrivelo.com',
                'star_rating' => 5,
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'policies' => [
                    'cancellation' => 'Free cancellation up to 24 hours before check-in',
                    'pets' => 'Pets are welcome with additional fee',
                    'smoking' => 'Non-smoking property'
                ],
                'amenities' => [
                    'wifi', 'parking', 'pool', 'gym', 'spa', 'restaurant',
                    'room_service', 'concierge', 'business_center', 'airport_shuttle'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800'
                ],
                'status' => 'approved',
                'is_active' => true,
                'is_featured' => true
            ]
        );

        // Create test rooms with different types
        $roomTypes = [
            ['type' => 'Standard', 'price' => 150, 'count' => 20],
            ['type' => 'Deluxe', 'price' => 220, 'count' => 15],
            ['type' => 'Suite', 'price' => 350, 'count' => 8],
            ['type' => 'Presidential', 'price' => 750, 'count' => 2]
        ];

        $allRooms = [];
        foreach ($roomTypes as $roomType) {
            for ($i = 1; $i <= $roomType['count']; $i++) {
                $roomNumber = $roomType['type'][0] . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                $capacity = match($roomType['type']) {
                    'Presidential' => 4,
                    'Suite' => 3,
                    default => 2
                };
                
                $size = match($roomType['type']) {
                    'Presidential' => 120,
                    'Suite' => 80,
                    'Deluxe' => 45,
                    default => 35
                };
                
                $room = Room::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'room_number' => $roomNumber],
                    [
                        'name' => "{$roomType['type']} Room {$roomNumber}",
                        'type' => strtolower($roomType['type']),
                        'base_price' => $roomType['price'],
                        'weekend_price' => $roomType['price'] * 1.2,
                        'holiday_price' => $roomType['price'] * 1.5,
                        'capacity' => $capacity,
                        'beds' => $capacity > 2 ? 2 : 1,
                        'bed_type' => $capacity > 2 ? 'king' : 'queen',
                        'size_sqft' => $size,
                        'description' => "Beautiful {$roomType['type']} room with modern amenities",
                        'amenities' => [
                            'wifi', 'tv', 'ac', 'minibar', 'safe', 'balcony'
                        ],
                        'features' => [
                            'city_view', 'private_bathroom', 'work_desk'
                        ],
                        'images' => [
                            'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600'
                        ],
                        'status' => 'available',
                        'is_available' => true,
                        'is_active' => true,
                        'is_smoking' => false,
                        'is_accessible' => rand(0, 4) === 0, // 20% accessible rooms
                        'floor_number' => rand(2, 15)
                    ]
                );
                $allRooms[] = $room;
            }
        }

        // Generate historical booking and payment data
        $this->generateHistoricalData($hotel, $allRooms, $customers);
    }

    private function generateHistoricalData($hotel, $rooms, $customers)
    {
        // Generate data for the last 12 months
        $startDate = Carbon::now()->subMonths(12);
        $endDate = Carbon::now();

        $bookingStatuses = ['confirmed', 'checked_out', 'cancelled'];
        $bookingWeights = [60, 30, 10]; // 60% confirmed, 30% checked_out, 10% cancelled

        // Generate bookings across different months with seasonal variations
        for ($month = 0; $month < 12; $month++) {
            $monthStart = $startDate->copy()->addMonths($month)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            // Seasonal booking variations (summer and winter holidays are peak)
            $seasonalMultiplier = in_array($monthStart->month, [6, 7, 8, 12, 1]) ? 1.5 : 1.0;
            $baseBookings = rand(15, 25);
            $monthlyBookings = (int)($baseBookings * $seasonalMultiplier);

            for ($i = 0; $i < $monthlyBookings; $i++) {
                $customer = $customers[array_rand($customers)];
                $room = $rooms[array_rand($rooms)];
                
                // Random booking dates within the month
                $checkIn = $monthStart->copy()->addDays(rand(0, $monthStart->daysInMonth - 3));
                $stayDuration = rand(1, 7); // 1-7 nights
                $checkOut = $checkIn->copy()->addDays($stayDuration);
                
                // Don't create future bookings as checked_out
                $status = $this->weightedRandom($bookingStatuses, $bookingWeights);
                if ($checkOut->isFuture() && $status === 'checked_out') {
                    $status = 'confirmed';
                }

                $totalAmount = $room->base_price * $stayDuration;
                $taxAmount = $totalAmount * 0.15; // 15% tax
                $finalAmount = $totalAmount + $taxAmount;

                // Create payment record
                $paymentStatus = match($status) {
                    'confirmed', 'checked_out' => 'completed',
                    'cancelled' => rand(0, 1) ? 'refunded' : 'completed',
                    default => 'pending'
                };

                $booking = Booking::create([
                    'booking_number' => 'TRV-' . date('Y') . '-' . rand(100000, 999999),
                    'user_id' => $customer->id,
                    'hotel_id' => $hotel->id,
                    'room_id' => $room->id,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'nights' => $stayDuration,
                    'adults' => rand(1, $room->capacity),
                    'children' => 0,
                    'room_rate' => $room->base_price,
                    'subtotal' => $totalAmount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $finalAmount,
                    'status' => $status,
                    'payment_status' => $paymentStatus === 'completed' ? 'paid' : 'pending',
                    'special_requests' => rand(0, 1) ? 'Late check-in requested' : null,
                    'created_at' => $checkIn->copy()->subDays(rand(1, 30)), // Booked 1-30 days in advance
                    'updated_at' => $checkIn->copy()->subDays(rand(1, 30))
                ]);

                // Create payment record

                Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $customer->id,
                    'transaction_id' => 'txn_' . uniqid(),
                    'amount' => $finalAmount,
                    'currency' => 'USD',
                    'type' => 'payment',
                    'method' => $this->weightedRandom(['stripe', 'paypal'], [80, 20]),
                    'status' => $paymentStatus,
                    'gateway' => 'stripe',
                    'net_amount' => $finalAmount * 0.97, // 3% gateway fee
                    'fee_amount' => $finalAmount * 0.03,
                    'processed_at' => $booking->created_at,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->created_at
                ]);

                // Add reviews for checked_out bookings (70% chance)
                if ($status === 'checked_out' && rand(1, 100) <= 70) {
                    Review::create([
                        'user_id' => $customer->id,
                        'hotel_id' => $hotel->id,
                        'booking_id' => $booking->id,
                        'rating' => $this->weightedRandom([5, 4, 3, 2, 1], [40, 35, 15, 7, 3]),
                        'cleanliness_rating' => rand(3, 5),
                        'service_rating' => rand(3, 5),
                        'location_rating' => rand(4, 5),
                        'value_rating' => rand(3, 5),
                        'title' => $this->getRandomReviewTitle(),
                        'review' => $this->getRandomReviewComment(),
                        'is_verified' => true,
                        'status' => 'approved',
                        'stayed_at' => $checkOut,
                        'created_at' => $checkOut->copy()->addDays(rand(1, 7)),
                        'updated_at' => $checkOut->copy()->addDays(rand(1, 7))
                    ]);
                }
            }
        }

        $this->command->info("Generated historical data for {$hotel->name}");
        $this->command->info("- Total bookings: " . Booking::whereHas('room', fn($q) => $q->where('hotel_id', $hotel->id))->count());
        $this->command->info("- Total payments: " . Payment::whereHas('booking', fn($q) => $q->whereHas('room', fn($q2) => $q2->where('hotel_id', $hotel->id)))->count());
        $this->command->info("- Total reviews: " . Review::where('hotel_id', $hotel->id)->count());
    }

    private function weightedRandom($values, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($weights as $index => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $values[$index];
            }
        }
        
        return $values[0];
    }

    private function getRandomReviewTitle()
    {
        $titles = [
            'Excellent stay!',
            'Great hotel with amazing service',
            'Perfect location and comfort',
            'Wonderful experience',
            'Highly recommended',
            'Outstanding hospitality',
            'Beautiful property',
            'Exceeded expectations',
            'Great value for money',
            'Will definitely return'
        ];

        return $titles[array_rand($titles)];
    }

    private function getRandomReviewComment()
    {
        $comments = [
            'Had a wonderful time at this hotel. The staff was very friendly and helpful.',
            'Clean rooms, great location, and excellent service. Would definitely stay again.',
            'The hotel exceeded our expectations. Beautiful property with top-notch amenities.',
            'Perfect for our vacation. The room was spacious and comfortable.',
            'Great experience overall. The breakfast was delicious and the staff was professional.',
            'Lovely hotel in a prime location. Easy access to attractions and restaurants.',
            'The service was impeccable and the facilities were well-maintained.',
            'Comfortable beds, clean bathrooms, and friendly staff. What more could you ask for?',
            'This hotel offers great value for money. Highly recommend to other travelers.',
            'From check-in to check-out, everything was smooth and pleasant.'
        ];

        return $comments[array_rand($comments)];
    }
}