<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return $setting->decodedValue();
    }

    public static function setValue(string $key, $value): self
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = static::normalizeValue($value);
        $setting->save();

        return $setting;
    }

    public static function getIntArray(string $key): array
    {
        $value = static::getValue($key, '');

        if (is_array($value)) {
            return array_values(array_filter(array_map(function ($item) {
                $number = (int) $item;
                return $number > 0 ? $number : null;
            }, $value)));
        }

        if (is_string($value)) {
            $parts = preg_split('/\s*,\s*/', trim($value), -1, PREG_SPLIT_NO_EMPTY);

            if (!$parts) {
                return [];
            }

            return array_values(array_filter(array_map(function ($item) {
                $number = (int) $item;
                return $number > 0 ? $number : null;
            }, $parts)));
        }

        return [];
    }

    public static function getJson(string $key, array $default = []): array
    {
        $value = static::getValue($key, $default);

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : $default;
        }

        return $default;
    }

    protected static function normalizeValue($value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }

    protected function decodedValue()
    {
        $value = $this->value;

        if (!is_string($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $value;
    }
}
