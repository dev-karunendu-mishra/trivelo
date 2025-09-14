<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            // General Hotel Amenities
            ['name' => 'Free WiFi', 'category' => 'connectivity', 'type' => 'both', 'icon' => 'wifi', 'is_premium' => false],
            ['name' => '24/7 Front Desk', 'category' => 'general', 'type' => 'hotel', 'icon' => 'clock', 'is_premium' => false],
            ['name' => 'Swimming Pool', 'category' => 'wellness', 'type' => 'hotel', 'icon' => 'swimming-pool', 'is_premium' => true],
            ['name' => 'Fitness Center', 'category' => 'wellness', 'type' => 'hotel', 'icon' => 'dumbbell', 'is_premium' => false],
            ['name' => 'Spa', 'category' => 'wellness', 'type' => 'hotel', 'icon' => 'spa', 'is_premium' => true],
            ['name' => 'Restaurant', 'category' => 'food', 'type' => 'hotel', 'icon' => 'utensils', 'is_premium' => false],
            ['name' => 'Bar/Lounge', 'category' => 'food', 'type' => 'hotel', 'icon' => 'glass-martini', 'is_premium' => false],
            ['name' => 'Room Service', 'category' => 'food', 'type' => 'hotel', 'icon' => 'bell', 'is_premium' => true],
            ['name' => 'Parking', 'category' => 'general', 'type' => 'hotel', 'icon' => 'parking', 'is_premium' => false],
            ['name' => 'Valet Parking', 'category' => 'general', 'type' => 'hotel', 'icon' => 'car', 'is_premium' => true],

            // Business Amenities
            ['name' => 'Business Center', 'category' => 'business', 'type' => 'hotel', 'icon' => 'briefcase', 'is_premium' => false],
            ['name' => 'Meeting Rooms', 'category' => 'business', 'type' => 'hotel', 'icon' => 'users', 'is_premium' => true],
            ['name' => 'Conference Facilities', 'category' => 'business', 'type' => 'hotel', 'icon' => 'presentation', 'is_premium' => true],

            // Entertainment
            ['name' => 'Casino', 'category' => 'entertainment', 'type' => 'hotel', 'icon' => 'dice', 'is_premium' => true],
            ['name' => 'Golf Course', 'category' => 'entertainment', 'type' => 'hotel', 'icon' => 'golf-ball', 'is_premium' => true],
            ['name' => 'Tennis Court', 'category' => 'entertainment', 'type' => 'hotel', 'icon' => 'tennis-ball', 'is_premium' => true],

            // Family Amenities
            ['name' => 'Kids Club', 'category' => 'family', 'type' => 'hotel', 'icon' => 'child', 'is_premium' => false],
            ['name' => 'Playground', 'category' => 'family', 'type' => 'hotel', 'icon' => 'playground', 'is_premium' => false],
            ['name' => 'Babysitting Services', 'category' => 'family', 'type' => 'hotel', 'icon' => 'baby', 'is_premium' => true],

            // Accessibility
            ['name' => 'Wheelchair Accessible', 'category' => 'accessibility', 'type' => 'both', 'icon' => 'wheelchair', 'is_premium' => false],
            ['name' => 'Elevator Access', 'category' => 'accessibility', 'type' => 'hotel', 'icon' => 'elevator', 'is_premium' => false],

            // Room Amenities
            ['name' => 'Air Conditioning', 'category' => 'general', 'type' => 'room', 'icon' => 'snowflake', 'is_premium' => false],
            ['name' => 'Heating', 'category' => 'general', 'type' => 'room', 'icon' => 'thermometer', 'is_premium' => false],
            ['name' => 'Mini Bar', 'category' => 'food', 'type' => 'room', 'icon' => 'wine-bottle', 'is_premium' => true],
            ['name' => 'Flat Screen TV', 'category' => 'entertainment', 'type' => 'room', 'icon' => 'tv', 'is_premium' => false],
            ['name' => 'Cable/Satellite TV', 'category' => 'entertainment', 'type' => 'room', 'icon' => 'satellite', 'is_premium' => false],
            ['name' => 'Coffee/Tea Maker', 'category' => 'food', 'type' => 'room', 'icon' => 'coffee', 'is_premium' => false],
            ['name' => 'Safe', 'category' => 'general', 'type' => 'room', 'icon' => 'lock', 'is_premium' => false],
            ['name' => 'Balcony', 'category' => 'general', 'type' => 'room', 'icon' => 'balcony', 'is_premium' => true],
            ['name' => 'Ocean View', 'category' => 'general', 'type' => 'room', 'icon' => 'waves', 'is_premium' => true],
            ['name' => 'City View', 'category' => 'general', 'type' => 'room', 'icon' => 'city', 'is_premium' => false],
            ['name' => 'Jacuzzi', 'category' => 'wellness', 'type' => 'room', 'icon' => 'hot-tub', 'is_premium' => true],
            ['name' => 'Kitchenette', 'category' => 'food', 'type' => 'room', 'icon' => 'kitchen', 'is_premium' => true],
            ['name' => 'Work Desk', 'category' => 'business', 'type' => 'room', 'icon' => 'desk', 'is_premium' => false],
            ['name' => 'Iron/Ironing Board', 'category' => 'general', 'type' => 'room', 'icon' => 'iron', 'is_premium' => false],
            ['name' => 'Hair Dryer', 'category' => 'general', 'type' => 'room', 'icon' => 'hair-dryer', 'is_premium' => false],
        ];

        foreach ($amenities as $index => $amenityData) {
            Amenity::create(array_merge($amenityData, [
                'sort_order' => $index + 1,
                'is_active' => true,
            ]));
        }

        $this->command->info('Created ' . count($amenities) . ' amenities');
    }
}
