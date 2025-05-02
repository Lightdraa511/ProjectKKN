<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
    ];

    /**
     * Format value based on setting type
     *
     * @return mixed
     */
    public function getFormattedValueAttribute()
    {
        switch ($this->type) {
            case 'number':
                return is_numeric($this->value) ? (float) $this->value : 0;
            case 'boolean':
                return (bool) $this->value;
            case 'array':
                return json_decode($this->value, true) ?? [];
            default:
                return $this->value;
        }
    }

    /**
     * Get all settings as an associative array of key => value
     *
     * @return array
     */
    public static function getAll()
    {
        return self::pluck('value', 'key')->toArray();
    }

    /**
     * Get all settings as an associative array with formatted values
     *
     * @return array
     */
    public static function getAllFormatted()
    {
        $settings = self::all();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = $setting->formatted_value;
        }

        return $result;
    }

    /**
     * Check if registration is currently open
     *
     * @return bool
     */
    public static function isRegistrationOpen()
    {
        $settings = self::whereIn('key', ['registration_start', 'registration_end'])
            ->pluck('value', 'key')
            ->toArray();

        if (empty($settings['registration_start']) || empty($settings['registration_end'])) {
            return false;
        }

        $now = now();
        $startDate = \Carbon\Carbon::parse($settings['registration_start']);
        $endDate = \Carbon\Carbon::parse($settings['registration_end']);

        return $now->between($startDate, $endDate);
    }
}
