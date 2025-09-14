<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Paris, France',
                'description' => 'The City of Light awaits with its iconic landmarks, world-class cuisine, and romantic atmosphere.',
                'country' => 'France',
                'state' => 'Île-de-France',
                'city' => 'Paris',
                'image_url' => 'https://images.unsplash.com/photo-1502602898536-47ad22581b52?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Tokyo, Japan',
                'description' => 'Experience the perfect blend of traditional culture and modern innovation in Japan\'s bustling capital.',
                'country' => 'Japan',
                'state' => 'Tokyo',
                'city' => 'Tokyo',
                'image_url' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'New York, USA',
                'description' => 'The city that never sleeps offers endless entertainment, world-famous landmarks, and diverse culture.',
                'country' => 'United States',
                'state' => 'New York',
                'city' => 'New York City',
                'image_url' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'London, UK',
                'description' => 'Rich history, royal palaces, and vibrant culture make London a must-visit destination.',
                'country' => 'United Kingdom',
                'state' => 'England',
                'city' => 'London',
                'image_url' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'name' => 'Dubai, UAE',
                'description' => 'Ultra-modern city with luxury shopping, stunning architecture, and world-class hospitality.',
                'country' => 'United Arab Emirates',
                'state' => 'Dubai',
                'city' => 'Dubai',
                'image_url' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 5,
            ],
            [
                'name' => 'Barcelona, Spain',
                'description' => 'Gothic architecture, beautiful beaches, and vibrant nightlife in Catalonia\'s capital.',
                'country' => 'Spain',
                'state' => 'Catalonia',
                'city' => 'Barcelona',
                'image_url' => 'https://images.unsplash.com/photo-1539037116277-4db20889f2d4?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 6,
            ],
            [
                'name' => 'Sydney, Australia',
                'description' => 'Iconic Opera House, beautiful harbors, and stunning beaches await in Australia\'s largest city.',
                'country' => 'Australia',
                'state' => 'New South Wales',
                'city' => 'Sydney',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 7,
            ],
            [
                'name' => 'Rome, Italy',
                'description' => 'Ancient history comes alive in the Eternal City with its magnificent ruins and Renaissance art.',
                'country' => 'Italy',
                'state' => 'Lazio',
                'city' => 'Rome',
                'image_url' => 'https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=800',
                'is_popular' => true,
                'is_active' => true,
                'display_order' => 8,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
