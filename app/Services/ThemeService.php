<?php

namespace App\Services;

use App\Models\Theme;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ThemeService
{
    private const CACHE_KEY = 'active_theme';
    private const CACHE_DURATION = 3600; // 1 hour

    /**
     * Available theme designs with their UI configurations
     */
    private const THEME_DESIGNS = [
        'modern' => [
            'display_name' => 'Modern Design',
            'description' => 'Card-based design with glassmorphism effects and contemporary UI',
            'layout' => 'themes.modern.layout',
            'navigation' => 'themes.modern.navigation',
            'footer' => 'themes.modern.footer',
            'hero' => 'themes.modern.hero',
            'components_path' => 'themes/modern',
            'css_framework' => 'tailwind',
            'features' => ['glassmorphism', 'animations', 'gradient_buttons', 'card_layouts']
        ],
        'classic' => [
            'display_name' => 'Classic Design',
            'description' => 'Traditional hotel website with elegant typography and formal layout',
            'layout' => 'themes.classic.layout',
            'navigation' => 'themes.classic.navigation',
            'footer' => 'themes.classic.footer',
            'hero' => 'themes.classic.hero',
            'components_path' => 'themes/classic',
            'css_framework' => 'bootstrap',
            'features' => ['elegant_typography', 'formal_layout', 'rich_textures', 'traditional_colors']
        ],
        'minimal' => [
            'display_name' => 'Minimal Design',
            'description' => 'Clean lines with lots of whitespace and simple navigation',
            'layout' => 'themes.minimal.layout',
            'navigation' => 'themes.minimal.navigation',
            'footer' => 'themes.minimal.footer',
            'hero' => 'themes.minimal.hero',
            'components_path' => 'themes/minimal',
            'css_framework' => 'tailwind',
            'features' => ['clean_typography', 'whitespace', 'simple_navigation', 'monochrome']
        ]
    ];

    /**
     * Get the currently active theme design
     */
    public function getActiveTheme(): ?Theme
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            return Theme::getActive();
        });
    }

    /**
     * Get theme design configuration
     */
    public function getThemeDesign(string $themeName = null): array
    {
        $themeName = $themeName ?: $this->getCurrentTheme();
        return self::THEME_DESIGNS[$themeName] ?? self::THEME_DESIGNS['modern'];
    }

    /**
     * Get layout file for current theme
     */
    public function getThemeLayout(): string
    {
        $design = $this->getThemeDesign();
        return $design['layout'];
    }

    /**
     * Get navigation component for current theme
     */
    public function getThemeNavigation(): string
    {
        $design = $this->getThemeDesign();
        return $design['navigation'];
    }

    /**
     * Get footer component for current theme
     */
    public function getThemeFooter(): string
    {
        $design = $this->getThemeDesign();
        return $design['footer'];
    }

    /**
     * Get hero component for current theme
     */
    public function getThemeHero(): string
    {
        $design = $this->getThemeDesign();
        return $design['hero'];
    }

    /**
     * Check if theme layout exists
     */
    public function themeLayoutExists(string $themeName): bool
    {
        $design = $this->getThemeDesign($themeName);
        return View::exists($design['layout']);
    }

    /**
     * Get all available themes
     */
    public function getAvailableThemes()
    {
        return Theme::getAvailable();
    }

    /**
     * Get all themes (alias for getAvailableThemes for backward compatibility)
     */
    public function getAllThemes()
    {
        return $this->getAvailableThemes();
    }

    /**
     * Get current theme slug
     */
    public function getCurrentTheme(): string
    {
        $theme = $this->getActiveTheme();
        return $theme ? $theme->slug : 'default';
    }

    /**
     * Get current theme slug (static method for Blade templates)
     */
    public static function current(): string
    {
        $service = new static();
        $theme = $service->getActiveTheme();
        return $theme ? $theme->name : 'modern';
    }

    /**
     * Switch to a different theme design
     */
    public function switchTheme(string $themeName): bool
    {
        // Check if theme design exists
        if (!isset(self::THEME_DESIGNS[$themeName])) {
            return false;
        }

        $theme = Theme::where('name', $themeName)->first();
        
        if (!$theme) {
            // Create theme record if it doesn't exist
            $design = self::THEME_DESIGNS[$themeName];
            $theme = Theme::create([
                'name' => $themeName,
                'display_name' => $design['display_name'],
                'description' => $design['description'],
                'color_scheme' => [],
                'typography_settings' => [],
                'layout_settings' => $design
            ]);
        }
        
        $theme->activate();
        $this->clearCache();
        
        return true;
    }

    /**
     * Get theme CSS class for current active theme
     */
    public function getThemeClass(): string
    {
        $theme = $this->getActiveTheme();
        return $theme ? $theme->css_class : 'theme-default';
    }

    /**
     * Get theme CSS variables for the active theme
     */
    public function getThemeCssVariables(): array
    {
        $theme = $this->getActiveTheme();
        
        if (!$theme) {
            return [];
        }

        $variables = [];
        
        // Add color scheme variables
        foreach ($theme->color_scheme as $key => $value) {
            $variables["--theme-{$key}"] = $value;
        }
        
        // Add typography variables
        if ($theme->typography_settings) {
            foreach ($theme->typography_settings as $key => $value) {
                $variables["--theme-{$key}"] = $value;
            }
        }
        
        // Add layout variables
        if ($theme->layout_settings) {
            foreach ($theme->layout_settings as $key => $value) {
                $variables["--theme-{$key}"] = $value;
            }
        }
        
        return $variables;
    }

    /**
     * Generate inline CSS for theme variables
     */
    public function getInlineCss(): string
    {
        $variables = $this->getThemeCssVariables();
        
        if (empty($variables)) {
            return '';
        }
        
        $css = ':root {' . PHP_EOL;
        
        foreach ($variables as $property => $value) {
            $css .= "    {$property}: {$value};" . PHP_EOL;
        }
        
        $css .= '}' . PHP_EOL;
        
        return $css;
    }

    /**
     * Get theme configuration for JavaScript
     */
    public function getThemeConfig(): array
    {
        $theme = $this->getActiveTheme();
        
        if (!$theme) {
            return [];
        }
        
        return [
            'name' => $theme->name,
            'displayName' => $theme->display_name,
            'colorScheme' => $theme->color_scheme,
            'cssClass' => $theme->css_class,
            'isDark' => $this->isDarkTheme($theme),
        ];
    }

    /**
     * Check if the theme is a dark theme
     */
    public function isDarkTheme(?Theme $theme = null): bool
    {
        $theme = $theme ?: $this->getActiveTheme();
        
        if (!$theme) {
            return false;
        }
        
        // Check if the theme has dark background colors
        $colorScheme = $theme->color_scheme;
        
        if (isset($colorScheme['body_bg'])) {
            return $this->isColorDark($colorScheme['body_bg']);
        }
        
        return in_array($theme->name, ['luxury', 'dark']);
    }

    /**
     * Determine if a color is dark
     */
    private function isColorDark(string $color): bool
    {
        // Remove # if present
        $color = ltrim($color, '#');
        
        // Convert to RGB
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
        
        // Calculate brightness
        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        
        return $brightness < 128;
    }

    /**
     * Create a custom theme
     */
    public function createCustomTheme(array $themeData): Theme
    {
        return Theme::create($themeData);
    }

    /**
     * Update theme settings
     */
    public function updateThemeSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            ThemeSetting::set($key, $value);
        }
        
        $this->clearCache();
    }

    /**
     * Get theme settings
     */
    public function getThemeSettings(): array
    {
        return ThemeSetting::getAllSettings();
    }

    /**
     * Export theme configuration
     */
    public function exportTheme(Theme $theme): array
    {
        return [
            'name' => $theme->name,
            'display_name' => $theme->display_name,
            'description' => $theme->description,
            'color_scheme' => $theme->color_scheme,
            'typography_settings' => $theme->typography_settings,
            'layout_settings' => $theme->layout_settings,
            'export_date' => now()->toISOString(),
            'version' => '1.0'
        ];
    }

    /**
     * Import theme configuration
     */
    public function importTheme(array $themeData): Theme
    {
        // Validate required fields
        $required = ['name', 'display_name', 'color_scheme'];
        foreach ($required as $field) {
            if (!isset($themeData[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }
        
        return Theme::updateOrCreate(
            ['name' => $themeData['name']],
            array_intersect_key($themeData, array_flip([
                'name', 'display_name', 'description', 'color_scheme',
                'typography_settings', 'layout_settings'
            ]))
        );
    }

    /**
     * Clear theme cache
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Generate theme preview
     */
    public function generatePreview(Theme $theme): string
    {
        // This would generate a preview image for the theme
        // For now, return a placeholder path
        return "themes/{$theme->name}-preview.jpg";
    }

    /**
     * Get theme fonts to load
     */
    public function getThemeFonts(): array
    {
        $theme = $this->getActiveTheme();
        
        if (!$theme || !$theme->typography_settings) {
            return [];
        }
        
        $fonts = [];
        $typography = $theme->typography_settings;
        
        // Extract Google Fonts from font family settings
        if (isset($typography['font_family'])) {
            $fonts = $this->extractGoogleFonts($typography['font_family']);
        }
        
        if (isset($typography['font_family_serif'])) {
            $fonts = array_merge($fonts, $this->extractGoogleFonts($typography['font_family_serif']));
        }
        
        return array_unique($fonts);
    }

    /**
     * Extract Google Fonts from font family string
     */
    private function extractGoogleFonts(string $fontFamily): array
    {
        $googleFonts = [];
        $knownGoogleFonts = [
            'Inter', 'Poppins', 'Montserrat', 'Playfair Display', 'Dancing Script'
        ];
        
        foreach ($knownGoogleFonts as $font) {
            if (strpos($fontFamily, $font) !== false) {
                $googleFonts[] = str_replace(' ', '+', $font);
            }
        }
        
        return $googleFonts;
    }
}