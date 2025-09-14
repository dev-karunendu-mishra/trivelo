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
                'name' => 'modern',
                'display_name' => 'Modern Design',
                'description' => 'Card-based design with glassmorphism effects and contemporary UI',
                'color_scheme' => [
                    'primary' => '#3B82F6',
                    'secondary' => '#8B5CF6',
                    'accent' => '#06D6A0',
                    'background' => '#0F172A',
                    'surface' => '#1E293B',
                    'text' => '#F8FAFC'
                ],
                'typography_settings' => [
                    'font_family' => 'Inter, system-ui, sans-serif',
                    'heading_font' => 'Poppins, sans-serif'
                ],
                'layout_settings' => [
                    'layout' => 'themes.modern.layout',
                    'navigation' => 'themes.modern.navigation',
                    'footer' => 'themes.modern.footer',
                    'hero' => 'themes.modern.hero',
                    'css_framework' => 'tailwind'
                ],
                'is_active' => false,
                'is_default' => false,
                'sort_order' => 1
            ],
            [
                'name' => 'classic',
                'display_name' => 'Classic Design',
                'description' => 'Traditional hotel website with elegant typography and formal layout',
                'color_scheme' => [
                    'primary' => '#C9A961',
                    'secondary' => '#1B365D',
                    'accent' => '#B8941C',
                    'background' => '#F8F6F0',
                    'surface' => '#FFFFFF',
                    'text' => '#2C2C2C'
                ],
                'typography_settings' => [
                    'font_family' => 'Crimson Text, serif',
                    'heading_font' => 'Playfair Display, serif'
                ],
                'layout_settings' => [
                    'layout' => 'themes.classic.layout',
                    'navigation' => 'themes.classic.navigation',
                    'footer' => 'themes.classic.footer',
                    'hero' => 'themes.classic.hero',
                    'css_framework' => 'bootstrap'
                ],
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'minimal',
                'display_name' => 'Minimal Design',
                'description' => 'Clean lines with lots of whitespace and simple navigation',
                'color_scheme' => [
                    'primary' => '#1F2937',
                    'secondary' => '#6B7280',
                    'accent' => '#10B981',
                    'background' => '#FFFFFF',
                    'surface' => '#F9FAFB',
                    'text' => '#111827'
                ],
                'typography_settings' => [
                    'font_family' => 'Inter, system-ui, sans-serif',
                    'heading_font' => 'Inter, system-ui, sans-serif'
                ],
                'layout_settings' => [
                    'layout' => 'themes.minimal.layout',
                    'navigation' => 'themes.minimal.navigation',
                    'footer' => 'themes.minimal.footer',
                    'hero' => 'themes.minimal.hero',
                    'css_framework' => 'tailwind'
                ],
                'is_active' => false,
                'is_default' => false,
                'sort_order' => 3
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
