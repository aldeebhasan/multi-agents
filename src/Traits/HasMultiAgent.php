<?php

namespace Aldeebhasan\MultiAgents\Traits;

use Aldeebhasan\MultiAgents\Models\Device;
use Illuminate\Database\Eloquent\Relations\Relation;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

trait HasMultiAgent
{
    /**
     *get all  the devices linked with the current user
     * @return Relation
     */

    public function devices()
    {
        return $this->morphMany(Device::class, 'ownerable');
    }

    /**
     * link single/multiple device/s with the user object
     * @param $devices
     * @return void
     */
    public function linkDevices($devices): void
    {
        if (is_array($devices) ) {
            foreach ($devices as $device) {
                $this->devices()->save($device);
            }
            return;
        } elseif ($devices instanceof Device) {
            $this->devices()->save($devices);
            return;
        }
        throw new NotFoundResourceException("The input type not supported");
    }

    /**
     * unlink single/multiple device/s with the user object
     * @param $devices
     * @return void
     */
    public function unlinkDevices($devices): void
    {
        if (is_array($devices)) {
            foreach ($devices as $device) {
                $this->devices()->where('id', $device->id)
                    ->update(['ownerable_id' => null, 'ownerable_type' => null]);
            }
            return;
        } elseif ($devices instanceof Device) {
            $this->devices()->where('id', $devices->id)
                ->update(['ownerable_id' => null, 'ownerable_type' => null]);
            return;
        }
        throw new NotFoundResourceException("The input type not supported");
    }

    /**
     * unlink all the devices connected with the current user
     * @return void
     */
    public function unlinkAll(): void
    {
        $this->devices()->update(['ownerable_id' => null, 'ownerable_type' => null]);
    }



}
