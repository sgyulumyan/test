<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Settings extends Model
{
    protected $fillable = ['key', 'value'];

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->firstOrFail();
    }

    public static function getValue(string $key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function setValue(string $key, string $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
