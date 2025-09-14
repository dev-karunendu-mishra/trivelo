<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'default',
                'display_name' => 'Default Theme',
                'description' => 'Classic hotel booking theme with professional blue color scheme and clean design.',
                'color_scheme' => [
                    'primary' => '#007bff',
                    'secondary' => '#6c757d',
                    'success' => '#28a745',
                    'info' => '#17a2b8',
                    'warning' => '#ffc107',
                    'danger' => '#dc3545',
                    'light' => '#f8f9fa',
                    'dark' => '#343a40',
                    'hotel_accent' => '#ff6b35',
                    'hotel_gold' => '#ffd700',
                    'rating_gold' => '#ffb400'
                ],
                'typography_settings' => [
                    'font_family' => 'Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                    'font_size_base' => '1rem',
                    'line_height_base' => 1.6
                ],
                'layout_settings' => [
                    'border_radius' => '8px',
                    'box_shadow' => '0 2px 8px rgba(0, 0, 0, 0.1)',
                    'container_max_width' => '1200px'
                ],
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'modern',
                'display_name' => 'Modern Theme',
                'description' => 'Contemporary tech-forward design with indigo gradients and glass morphism effects.',
                'color_scheme' => [
                    'primary' => '#6366f1',
                    'secondary' => '#64748b',
                    'success' => '#10b981',
                    'info' => '#06b6d4',
                    'warning' => '#f59e0b',
                    'danger' => '#ef4444',
                    'light' => '#f1f5f9',
                    'dark' => '#0f172a',
                    'hotel_accent' => '#ec4899',
                    'hotel_gold' => '#f59e0b',
                    'rating_gold' => '#fbbf24'
                ],
                'typography_settings' => [
                    'font_family' => 'Inter, system-ui, -apple-system, sans-serif',
                    'font_size_base' => '1rem',
                    'line_height_base' => 1.6
                ],
                'layout_settings' => [
                    'border_radius' => '10px',
                    'box_shadow' => '0 10px 25px rgba(99, 102, 241, 0.1)',
                    'container_max_width' => '1200px'
                ],
                'is_active' => false,
                'is_default' => false,
                'sort_order' => 2
            ],
            [
                'name' => 'luxury',
                'display_name' => 'Luxury Theme',
                'description' => 'Premium elegant design with gold and black colors, sophisticated typography.',
                'color_scheme' => [
                    'primary' => '#d4af37',
                    'secondary' => '#c0c0c0',
                    'success' => '#50c878',
                    'info' => '#6a0dad',
                    'warning' => '#ffd700',
                    'danger' => '#800020',
                    'light' => '#e5e4e2',
                    'dark' => '#0d0d0d',
                    'hotel_accent' => '#d4af37',
                    'hotel_gold' => '#ffd700',
                    'rating_gold' => '#d4af37'
                ],
                'typography_settings' => [
                    'font_family' => 'Montserrat, "Helvetica Neue", sans-serif',
                    'font_family_serif' => 'Playfair Display, Georgia, serif',
                    'font_size_base' => '1rem',
                    'line_height_base' => 1.7
                ],
                'layout_settings' => [
                    'border_radius' => '0px',
                    'box_shadow' => '0 8px 20px rgba(212, 175, 55, 0.2)',
                    'container_max_width' => '1200px'
                ],
                'is_active' => false,
                'is_default' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'minimal',
                'display_name' => 'Minimal Theme',
                'description' => 'Clean simplified design with monochrome palette and generous white space.',
                'color_scheme' => [
                    'primary' => '#000000',
                    'secondary' => '#9e9e9e',
                    'success' => '#4caf50',
                    'info' => '#2196f3',
                    'warning' => '#ff9800',
                    'danger' => '#f44336',
                    'light' => '#fafafa',
                    'dark' => '#212121',
                    'hotel_accent' => '#2196f3',
                    'hotel_gold' => '#ffc107',
                    'rating_gold' => '#000000'
                ],
                'typography_settings' => [
                    'font_family' => 'Inter, "Helvetica Neue", Arial, sans-serif',
                    'font_size_base' => '1rem',
                    'line_height_base' => 1.8
                ],
                'layout_settings' => [
                    'border_radius' => '0px',
                    'box_shadow' => 'none',
                    'container_max_width' => '1200px'
                ],
                'is_active' => false,
                'is_default' => false,
                'sort_order' => 4
            ]
        ];

        foreach ($themes as $theme) {
            \App\Models\Theme::updateOrCreate(
                ['name' => $theme['name']],
                $theme
            );
        }
    }
}
