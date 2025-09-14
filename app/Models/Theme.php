<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'color_scheme',
        'typography_settings',
        'layout_settings',
        'preview_image',
        'is_active',
        'is_default',
        'sort_order'
    ];

    protected $casts = [
        'color_scheme' => 'array',
        'typography_settings' => 'array',
        'layout_settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the active theme
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first() ?? self::getDefault();
    }

    /**
     * Get the default theme
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first() ??
               self::where('name', 'default')->first();
    }

    /**
     * Set this theme as active
     */
    public function activate()
    {
        // Deactivate all themes
        self::where('is_active', true)->update(['is_active' => false]);
        
        // Activate this theme
        $this->update(['is_active' => true]);
    }

    /**
     * Get available themes
     */
    public static function getAvailable()
    {
        return self::orderBy('sort_order')->orderBy('display_name')->get();
    }

    /**
     * Get theme CSS class name
     */
    public function getCssClassAttribute()
    {
        return 'theme-' . $this->name;
    }

    /**
     * Get theme slug (alias for name)
     */
    public function getSlugAttribute()
    {
        return $this->name;
    }

    /**
     * Get theme preview URL
     */
    public function getPreviewUrlAttribute()
    {
        if ($this->preview_image) {
            return asset('storage/' . $this->preview_image);
        }
        
        return asset('images/themes/' . $this->name . '-preview.jpg');
    }
}
