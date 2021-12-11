<?php

namespace Aldeebhasan\MultiAgents\Middleware;

use Aldeebhasan\MultiAgents\Exceptions\DeviceAuthenticationException;
use Aldeebhasan\MultiAgents\Exceptions\DeviceExpiredTokenException;
use Aldeebhasan\MultiAgents\Models\Device;
use Carbon\Carbon;
use Closure;

class DeviceAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $owner = null)
    {

        $token = '';
        if ($request->hasHeader('Device-Token')) {
            $token = $request->header('Device-Token');
        } elseif ($request->has('Device-Token')) {
            $token = $request->get('Device-Token');
        } else {
            $this->unauthenticated();
        }
        $device = Device::where([
            ['token', $token],
        ])->when($owner, function ($query, $owner) {
            return $query->where('ownerable_type', $owner);
        })->first();
        if ($device == null) {
            $this->unauthenticated();
        } elseif ($device->expired_at->isPast()) {
            $this->expired();
        } else {
            $device->update(["last_used_at" => Carbon::now()->toDateTimeString()]);
        }
        $request->request->add(['device_id' => $device->id]);
        return $next($request);
    }

    /**
     * Handle an unauthenticated device.
     *
     * @return void
     *
     * @throws DeviceAuthenticationException
     */
    protected function unauthenticated(): void
    {
        throw new DeviceAuthenticationException('Unauthenticated Device.');
    }

    /**
     * Handle expired device.
     *
     * @return void
     *
     * @throws DeviceExpiredTokenException
     */
    protected function expired(): void
    {
        throw new DeviceExpiredTokenException('Device token expired.');
    }
}
