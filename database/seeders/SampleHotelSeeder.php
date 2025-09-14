<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Destination;
use App\Models\HotelLocation;
use App\Models\HotelImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SampleHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create hotel manager role
        $hotelManagerRole = Role::firstOrCreate(['name' => 'hotel_manager']);

        // Create sample hotel managers
        $manager1 = User::firstOrCreate(
            ['email' => 'manager1@example.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $manager1->assignRole($hotelManagerRole);

        $manager2 = User::firstOrCreate(
            ['email' => 'manager2@example.com'],
            [
                'name' => 'Sarah Johnson',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $manager2->assignRole($hotelManagerRole);

        // Get destinations
        $paris = Destination::where('city', 'Paris')->first();
        $tokyo = Destination::where('city', 'Tokyo')->first();
        $newYork = Destination::where('city', 'New York City')->first();
        $london = Destination::where('city', 'London')->first();

        // Create sample hotels
        $hotels = [
            [
                'user_id' => $manager1->id,
                'destination_id' => $paris->id ?? null,
                'name' => 'Grand Hotel Paris',
                'description' => 'Luxury hotel in the heart of Paris, offering stunning views of the Eiffel Tower and world-class amenities. Experience French elegance and hospitality at its finest.',
                'images' => [
                    'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=800',
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800',
                ],
                'email' => 'info@grandhotelparis.com',
                'phone' => '+33 1 40 15 95 00',
                'address' => '15 Avenue des Champs-Élysées',
                'city' => 'Paris',
                'state' => 'Île-de-France',
                'country' => 'France',
                'postal_code' => '75008',
                'latitude' => 48.8738,
                'longitude' => 2.2950,
                'star_rating' => 5,
                'average_rating' => 4.8,
                'total_reviews' => 1250,
                'status' => 'approved',
                'is_featured' => true,
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'user_id' => $manager2->id,
                'destination_id' => $tokyo->id ?? null,
                'name' => 'Tokyo Imperial Hotel',
                'description' => 'Traditional Japanese hospitality meets modern luxury in the heart of Tokyo. Experience authentic service and exquisite cuisine.',
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800',
                ],
                'email' => 'reservations@tokyoimperial.com',
                'phone' => '+81 3-3504-1111',
                'address' => '1-1-1 Uchisaiwaicho, Chiyoda City',
                'city' => 'Tokyo',
                'state' => 'Tokyo',
                'country' => 'Japan',
                'postal_code' => '100-8558',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
                'star_rating' => 5,
                'average_rating' => 4.7,
                'total_reviews' => 980,
                'status' => 'approved',
                'is_featured' => true,
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'user_id' => $manager1->id,
                'destination_id' => $newYork->id ?? null,
                'name' => 'Manhattan Plaza Hotel',
                'description' => 'Prime location in Midtown Manhattan with easy access to Broadway shows, Central Park, and world-famous shopping.',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
                    'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800',
                ],
                'email' => 'info@manhattanplaza.com',
                'phone' => '+1 212-555-0123',
                'address' => '768 5th Avenue',
                'city' => 'New York City',
                'state' => 'New York',
                'country' => 'United States',
                'postal_code' => '10019',
                'latitude' => 40.7614,
                'longitude' => -73.9776,
                'star_rating' => 4,
                'average_rating' => 4.5,
                'total_reviews' => 2100,
                'status' => 'approved',
                'is_featured' => true,
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'user_id' => $manager2->id,
                'destination_id' => $london->id ?? null,
                'name' => 'Royal London Hotel',
                'description' => 'Classic British elegance in the heart of London. Close to Buckingham Palace, Westminster Abbey, and the London Eye.',
                'images' => [
                    'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800',
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800',
                ],
                'email' => 'bookings@royallondonhotel.co.uk',
                'phone' => '+44 20 7930 8181',
                'address' => '25 Buckingham Gate',
                'city' => 'London',
                'state' => 'England',
                'country' => 'United Kingdom',
                'postal_code' => 'SW1E 6LB',
                'latitude' => 51.4994,
                'longitude' => -0.1356,
                'star_rating' => 4,
                'average_rating' => 4.6,
                'total_reviews' => 1850,
                'status' => 'approved',
                'is_featured' => true,
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'user_id' => $manager1->id,
                'destination_id' => $paris->id ?? null,
                'name' => 'Boutique Hotel Montmartre',
                'description' => 'Charming boutique hotel in the artistic district of Montmartre. Perfect for experiencing authentic Parisian culture.',
                'images' => [
                    'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800',
                ],
                'email' => 'info@montmartreboutique.com',
                'phone' => '+33 1 46 06 32 32',
                'address' => '18 Rue des Abbesses',
                'city' => 'Paris',
                'state' => 'Île-de-France',
                'country' => 'France',
                'postal_code' => '75018',
                'latitude' => 48.8848,
                'longitude' => 2.3377,
                'star_rating' => 3,
                'average_rating' => 4.3,
                'total_reviews' => 650,
                'status' => 'approved',
                'is_featured' => false,
                'is_active' => true,
                'verified_at' => now(),
            ],
            [
                'user_id' => $manager2->id,
                'destination_id' => $tokyo->id ?? null,
                'name' => 'Modern Tokyo Suites',
                'description' => 'Contemporary hotel with state-of-the-art facilities in Tokyo\'s business district. Perfect for both business and leisure travelers.',
                'images' => [
                    'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?w=800',
                ],
                'email' => 'reservations@moderntokyosuites.com',
                'phone' => '+81 3-6273-4100',
                'address' => '2-10-3 Nagatacho, Chiyoda City',
                'city' => 'Tokyo',
                'state' => 'Tokyo',
                'country' => 'Japan',
                'postal_code' => '100-0014',
                'latitude' => 35.6803,
                'longitude' => 139.7391,
                'star_rating' => 4,
                'average_rating' => 4.4,
                'total_reviews' => 890,
                'status' => 'approved',
                'is_featured' => false,
                'is_active' => true,
                'verified_at' => now(),
            ],
        ];

        foreach ($hotels as $hotelData) {
            $hotel = Hotel::create($hotelData);
            
            // Create hotel location
            HotelLocation::create([
                'hotel_id' => $hotel->id,
                'address' => $hotelData['address'],
                'city' => $hotelData['city'],
                'state' => $hotelData['state'],
                'country' => $hotelData['country'],
                'postal_code' => $hotelData['postal_code'],
                'latitude' => $hotelData['latitude'],
                'longitude' => $hotelData['longitude'],
            ]);
            
            // Create hotel images
            foreach ($hotelData['images'] as $index => $imageUrl) {
                HotelImage::create([
                    'hotel_id' => $hotel->id,
                    'image_url' => $imageUrl,
                    'alt_text' => $hotel->name . ' - Image ' . ($index + 1),
                    'is_primary' => $index === 0,
                    'display_order' => $index + 1,
                ]);
            }
        }
    }
}
