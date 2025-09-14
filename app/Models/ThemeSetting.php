<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'group',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    /**
     * Get setting value with appropriate casting
     */
    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Set setting value with appropriate casting
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            'json' => json_encode($value),
            default => $value,
        };
    }

    /**
     * Get setting by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group
            ]
        );
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)
                   ->orderBy('sort_order')
                   ->orderBy('key')
                   ->get()
                   ->pluck('value', 'key')
                   ->toArray();
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }
}
