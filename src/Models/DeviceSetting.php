<?php

namespace Aldeebhasan\MultiAgents\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeviceSetting extends Model
{
    protected $table = 'ma_device_setting';
    protected $fillable = [
        'device_id','setting_id','value'
    ];

}
