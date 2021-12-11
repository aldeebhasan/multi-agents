<?php

namespace Aldeebhasan\MultiAgents\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'ma_settings';
    protected $fillable = [
        'key'
    ];

    function devices()
    {
        $this->belongsToMany(Device::class, 'ma_device_setting');
    }

    /**
     * register a new setting object
     * @param string $key
     * @return Setting
     */
    static function register($key): Setting
    {
        return self::firstOrCreate(['key' => $key]);
    }

}
