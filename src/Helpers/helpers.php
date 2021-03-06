<?php

use \Aldeebhasan\MultiAgents\Models\Device;

if (!function_exists('getCurrentDevice')) {
    function getCurrentDevice(): ?Device
    {
        if (request()->has('device_id')) {
            return Device::find(request()->get('device_id'));
        }
        return null;
    }
}
