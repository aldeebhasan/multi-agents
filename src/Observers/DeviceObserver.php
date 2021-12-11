<?php

namespace Aldeebhasan\MultiAgents\Observers;

use Aldeebhasan\MultiAgents\Models\Device;
use Carbon\Carbon;

class DeviceObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param Device $device
     * @return void
     */
    public function creating(Device $device)
    {
        $device->token = bcrypt($device->uuid);
        $device->agent = request()->header('User-Agent');
        $device->expired_at = Carbon::now()->addDays(1)->toDateTimeString();
    }
}
