<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';

    public function updateSetting(string $key, string $value) : int
    {
        return $this->where('key', $key)->update(
            [
                'value' => $value,
                'updated_at' => now()
            ]);
    }

    public static function getSettingValue(string $key)
    {
        $query = self::where('key', $key)->latest('created_at')->first();
    
        if ($query) {
            return $query->value;
        } else {
            // Return a default value or an empty string, for example
            return ''; // You can modify this to return an appropriate default value
        }
    }
    
    
    public function getAllSettings()
    {
        return $this->all()->toArray();
    }
}
