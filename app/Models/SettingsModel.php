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
            throw new \Exception('Invalid key');
        }
    }
    
    public function getAllSettings()
    {
        return $this->all()->toArray();
    }
}
