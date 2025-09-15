<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create hotel manager user
        $manager = \App\Models\User::firstOrCreate(
            ['email' => 'manager@trivelo.com'],
            [
                'name' => 'Hotel Manager',
                'password' => bcrypt('password123'),
                'role' => 'hotel_manager',
                'is_active' => true,
                'last_login' => now(),
            ]
        );

        // Create a sample hotel for the manager
        $hotel = \App\Models\Hotel::firstOrCreate(
            ['name' => 'Grand Plaza Hotel'],
            [
                'user_id' => $manager->id,
                'slug' => 'grand-plaza-hotel',
                'description' => 'A luxury hotel in the heart of the city with modern amenities and exceptional service.',
                'address' => '123 Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'phone' => '+1-555-0123',
                'email' => 'info@grandplaza.com',
                'star_rating' => 5,
                'is_active' => true,
                'status' => 'approved',
                'amenities' => json_encode(['wifi', 'parking', 'restaurant', 'gym', 'spa', 'pool']),
                'policies' => json_encode([
                    'check_in_time' => '15:00',
                    'check_out_time' => '11:00',
                    'cancellation' => '24 hours before check-in',
                    'pets' => 'Pets are welcome with additional fee',
                    'smoking' => 'Non-smoking property',
                ]),
            ]
        );

        // Create sample rooms for the hotel
        $roomTypes = [
            ['type' => 'standard', 'name' => 'Standard Room', 'base_price' => 150],
            ['type' => 'deluxe', 'name' => 'Deluxe Room', 'base_price' => 220],
            ['type' => 'suite', 'name' => 'Executive Suite', 'base_price' => 350],
        ];

        foreach ($roomTypes as $index => $roomType) {
            for ($i = 1; $i <= 10; $i++) {
                $roomNumber = ($index + 1) . str_pad($i, 2, '0', STR_PAD_LEFT);
                
                \App\Models\Room::firstOrCreate(
                    ['hotel_id' => $hotel->id, 'room_number' => $roomNumber],
                    [
                        'name' => $roomType['name'] . ' ' . $roomNumber,
                        'description' => 'Comfortable ' . strtolower($roomType['name']) . ' with modern amenities.',
                        'type' => $roomType['type'],
                        'capacity' => $roomType['type'] === 'suite' ? 4 : 2,
                        'beds' => $roomType['type'] === 'suite' ? 2 : 1,
                        'bed_type' => $roomType['type'] === 'suite' ? 'king' : 'queen',
                        'base_price' => $roomType['base_price'],
                        'weekend_price' => $roomType['base_price'] * 1.2,
                        'size_sqft' => $roomType['type'] === 'suite' ? 600 : ($roomType['type'] === 'deluxe' ? 400 : 300),
                        'floor_number' => $index + 1,
                        'is_available' => true,
                        'is_active' => true,
                        'status' => 'available',
                        'amenities' => json_encode(['wifi', 'tv', 'ac', 'minibar']),
                        'features' => json_encode(['city_view', 'room_service']),
                    ]
                );
            }
        }

        $this->command->info('Hotel manager and sample hotel created/updated: ' . $manager->email);
        $this->command->info('Sample hotel: ' . $hotel->name . ' with ' . $hotel->rooms()->count() . ' rooms');
    }
}
