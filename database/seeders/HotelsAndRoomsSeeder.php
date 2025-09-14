<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use App\Models\Amenity;
use Illuminate\Database\Seeder;

class HotelsAndRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a hotel manager
        $hotelManager = User::role('hotel_manager')->first();

        if (!$hotelManager) {
            $this->command->error('No hotel manager found. Please run RolesAndPermissionsSeeder first.');
            return;
        }

        // Clear existing hotels and rooms for clean seeding (optional)
        $this->command->info('Clearing existing hotels and rooms...');
        Hotel::query()->forceDelete();
        Room::query()->forceDelete();

        // Create sample hotels
        $hotels = [
            [
                'name' => 'Trivelo Grand Hotel',
                'description' => 'A luxurious 5-star hotel in the heart of the city, offering world-class amenities and exceptional service.',
                'email' => 'info@trivelqgrand.com',
                'phone' => '+1-555-0101',
                'address' => '123 Grand Avenue',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'postal_code' => '10001',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'star_rating' => 5,
                'status' => 'approved',
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    '/images/hotels/grand-hotel-1.jpg',
                    '/images/hotels/grand-hotel-2.jpg',
                    '/images/hotels/grand-hotel-3.jpg'
                ],
                'policies' => [
                    'check_in' => '15:00',
                    'check_out' => '11:00',
                    'cancellation_policy' => 'Free cancellation up to 24 hours before check-in',
                    'pet_policy' => 'Pets allowed with additional fee',
                    'smoking_policy' => 'Non-smoking property'
                ],
                'verified_at' => now(),
            ],
            [
                'name' => 'Trivelo Business Inn',
                'description' => 'Modern business hotel with excellent connectivity and professional services.',
                'email' => 'reservations@trivelobusiness.com',
                'phone' => '+1-555-0102',
                'address' => '456 Business Plaza',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'country' => 'USA',
                'postal_code' => '90001',
                'latitude' => 34.0522,
                'longitude' => -118.2437,
                'star_rating' => 4,
                'status' => 'approved',
                'is_active' => true,
                'is_featured' => false,
                'images' => [
                    '/images/hotels/business-inn-1.jpg',
                    '/images/hotels/business-inn-2.jpg'
                ],
                'policies' => [
                    'check_in' => '14:00',
                    'check_out' => '12:00',
                    'cancellation_policy' => 'Free cancellation up to 48 hours before check-in',
                    'pet_policy' => 'No pets allowed',
                    'smoking_policy' => 'Designated smoking areas only'
                ],
                'verified_at' => now(),
            ],
            [
                'name' => 'Trivelo Beach Resort',
                'description' => 'Beachfront resort with stunning ocean views and tropical ambiance.',
                'email' => 'hello@trivelobeach.com',
                'phone' => '+1-555-0103',
                'address' => '789 Ocean Drive',
                'city' => 'Miami',
                'state' => 'FL',
                'country' => 'USA',
                'postal_code' => '33101',
                'latitude' => 25.7617,
                'longitude' => -80.1918,
                'star_rating' => 4,
                'status' => 'approved',
                'is_active' => true,
                'is_featured' => true,
                'images' => [
                    '/images/hotels/beach-resort-1.jpg',
                    '/images/hotels/beach-resort-2.jpg',
                    '/images/hotels/beach-resort-3.jpg',
                    '/images/hotels/beach-resort-4.jpg'
                ],
                'policies' => [
                    'check_in' => '16:00',
                    'check_out' => '10:00',
                    'cancellation_policy' => 'Free cancellation up to 72 hours before check-in',
                    'pet_policy' => 'Service animals only',
                    'smoking_policy' => 'Non-smoking property'
                ],
                'verified_at' => now(),
            ]
        ];

        foreach ($hotels as $hotelData) {
            $hotel = Hotel::create([
                'user_id' => $hotelManager->id,
                'name' => $hotelData['name'],
                'description' => $hotelData['description'],
                'email' => $hotelData['email'],
                'phone' => $hotelData['phone'],
                'address' => $hotelData['address'],
                'city' => $hotelData['city'],
                'state' => $hotelData['state'],
                'country' => $hotelData['country'],
                'postal_code' => $hotelData['postal_code'],
                'latitude' => $hotelData['latitude'],
                'longitude' => $hotelData['longitude'],
                'star_rating' => $hotelData['star_rating'],
                'status' => $hotelData['status'],
                'is_active' => $hotelData['is_active'],
                'is_featured' => $hotelData['is_featured'],
                'images' => $hotelData['images'],
                'policies' => $hotelData['policies'],
                'verified_at' => $hotelData['verified_at'],
            ]);

            // Attach random amenities to each hotel
            $amenityIds = Amenity::inRandomOrder()->take(rand(5, 12))->pluck('id');
            $hotel->amenities()->attach($amenityIds);

            $this->createRoomsForHotel($hotel);
        }

        $this->command->info('Created ' . Hotel::count() . ' hotels with their rooms and amenities.');
    }

    private function createRoomsForHotel(Hotel $hotel)
    {
        $roomTypes = [
            [
                'type' => 'Standard Room',
                'type_enum' => 'standard',
                'description' => 'Comfortable standard room with essential amenities.',
                'base_price' => 120.00,
                'capacity' => 2,
                'beds' => 1,
                'bed_type' => 'queen',
                'size' => 250,
                'count' => 20
            ],
            [
                'type' => 'Deluxe Room',
                'type_enum' => 'deluxe',
                'description' => 'Spacious deluxe room with city views and premium amenities.',
                'base_price' => 180.00,
                'capacity' => 2,
                'beds' => 1,
                'bed_type' => 'king',
                'size' => 350,
                'count' => 15
            ],
            [
                'type' => 'Family Suite',
                'type_enum' => 'family',
                'description' => 'Large family suite with separate living area and multiple beds.',
                'base_price' => 280.00,
                'capacity' => 4,
                'beds' => 2,
                'bed_type' => 'queen',
                'size' => 550,
                'count' => 8
            ],
            [
                'type' => 'Presidential Suite',
                'type_enum' => 'presidential',
                'description' => 'Luxury presidential suite with premium amenities and stunning views.',
                'base_price' => 500.00,
                'capacity' => 2,
                'beds' => 1,
                'bed_type' => 'king',
                'size' => 800,
                'count' => 2
            ]
        ];

        foreach ($roomTypes as $roomType) {
            for ($i = 1; $i <= $roomType['count']; $i++) {
                $room = Room::create([
                    'hotel_id' => $hotel->id,
                    'room_number' => $this->generateRoomNumber($roomType['type'], $i),
                    'name' => $roomType['type'],
                    'type' => $roomType['type_enum'],
                    'description' => $roomType['description'],
                    'images' => $this->generateRoomImages($roomType['type']),
                    'base_price' => $roomType['base_price'],
                    'capacity' => $roomType['capacity'],
                    'beds' => $roomType['beds'],
                    'bed_type' => $roomType['bed_type'],
                    'size_sqft' => $roomType['size'],
                    'floor_number' => rand(1, 15),
                    'features' => $this->generateRoomFeatures($roomType['type']),
                    'is_available' => true,
                    'is_active' => true,
                ]);

                // Attach random amenities to rooms
                $roomAmenityIds = Amenity::whereIn('type', ['room', 'both'])
                    ->inRandomOrder()
                    ->take(rand(3, 8))
                    ->pluck('id');
                
                if ($roomAmenityIds->isNotEmpty()) {
                    $room->amenities()->attach($roomAmenityIds);
                }
            }
        }
    }

    private function generateRoomNumber($type, $index)
    {
        $typePrefix = match($type) {
            'Standard Room' => '1',
            'Deluxe Room' => '2',
            'Family Suite' => '3',
            'Presidential Suite' => '5',
            default => '1'
        };

        return $typePrefix . str_pad($index, 2, '0', STR_PAD_LEFT);
    }

    private function generateRoomImages($type)
    {
        $baseImages = [
            'Standard Room' => [
                '/images/rooms/standard-1.jpg',
                '/images/rooms/standard-2.jpg'
            ],
            'Deluxe Room' => [
                '/images/rooms/deluxe-1.jpg',
                '/images/rooms/deluxe-2.jpg',
                '/images/rooms/deluxe-3.jpg'
            ],
            'Family Suite' => [
                '/images/rooms/suite-1.jpg',
                '/images/rooms/suite-2.jpg',
                '/images/rooms/suite-3.jpg',
                '/images/rooms/suite-4.jpg'
            ],
            'Presidential Suite' => [
                '/images/rooms/presidential-1.jpg',
                '/images/rooms/presidential-2.jpg',
                '/images/rooms/presidential-3.jpg',
                '/images/rooms/presidential-4.jpg',
                '/images/rooms/presidential-5.jpg'
            ]
        ];

        return $baseImages[$type] ?? ['/images/rooms/default.jpg'];
    }

    private function generateRoomFeatures($type)
    {
        $baseFeatures = [
            'Air Conditioning',
            'Free Wi-Fi',
            'Private Bathroom',
            'Flat Screen TV',
            'Coffee Maker'
        ];

        $premiumFeatures = [
            'City View',
            'Balcony',
            'Mini Bar',
            'Room Service',
            'Bathrobe & Slippers',
            'Premium Toiletries',
            'Safe',
            'Work Desk'
        ];

        $luxuryFeatures = [
            'Ocean View',
            'Jacuzzi',
            'Butler Service',
            'Premium Mini Bar',
            'Marble Bathroom',
            'Walk-in Closet',
            'Living Area',
            'Dining Area'
        ];

        $features = $baseFeatures;

        if (in_array($type, ['Deluxe Room', 'Family Suite', 'Presidential Suite'])) {
            $features = array_merge($features, array_slice($premiumFeatures, 0, rand(3, 5)));
        }

        if (in_array($type, ['Presidential Suite'])) {
            $features = array_merge($features, array_slice($luxuryFeatures, 0, rand(3, 6)));
        }

        return $features;
    }
}
