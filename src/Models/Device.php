<?php

namespace Aldeebhasan\MultiAgents\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Device extends Model
{
    protected $table = 'ma_devices';
    protected $fillable = [
        'uuid', 'token', 'last_used_at', 'expired_at'
    ];
    protected $dates = [
        'expired_at'
    ];

    public function ownerable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->ownerable;
    }


    public function getAgentAttribute($attr)
    {
        $agent = new Agent();
        $agent->setUserAgent($attr);
        return $agent;

    }

    public function settings()
    {
        return $this->belongsToMany(Setting::class, 'ma_device_setting')->withPivot('value');
    }

    /**
     * retrive the current device settings
     * @return \Illuminate\Support\Collection
     */
    public function getSettings()
    {
        $data = [];
        foreach ($this->settings as $item) {
            $data[$item->key] = $item->pivot->value;
        }
        return collect($data);
    }

    /**
     * rigister new device with the providde uuid (if exist it will be returned directly)
     *
     * @param string $uuid: UUID string
     * @return Device
     */
    static function register($uuid) : Device
    {
        $device = self::firstOrCreate(['uuid' => $uuid]);
        $device->update(['expired_at' => Carbon::now()->addDays(1)->toDateTimeString()]);
        return $device;
    }

    /**
     * update the settings to mach the inputs
     * @param array $settings
     * @return void
     */
    public function syncSettings($settings): void
    {
        if (is_array($settings)) {
            $keys = array_keys($settings);
            $values = array_values($settings);
            $keys = Setting::whereIn('key', $keys)->get()->pluck('id');
            $data = array();
            foreach ($keys as $i => $key) {
                $data[$key] = ['value' => $values[$i]];
            }
            $this->settings()->sync($data);
            return;
        }
        throw new NotFoundResourceException("The input type not supported");
    }

    /**
     * add new settings to the current device
     * @param array $settings
     * @return void
     */
    public function addSettings($settings): void
    {
        if (is_array($settings)) {
            $keys = array_keys($settings);
            $keys = Setting::whereIn('key', $keys)->get()->pluck('id');
            $values = array_values($settings);
            foreach ($keys as $i => $key) {
                $this->settings()->attach($key, ['value' => $values[$i]]);
            }
            return;
        }
        throw new NotFoundResourceException("The input type not supported");
    }

    /**
     * remove settings to the current device
     * @param array|string $keys
     * @return void
     */
    public function deleteSettings($keys): void
    {
        if (is_array($keys)) {
            $keys = Setting::whereIn('key', $keys)->get()->pluck('id');
            foreach ($keys as $i => $key) {
                $this->settings()->detach($key);
            }
            return;
        }
        if (is_string($keys)) {
            $key = Setting::where('key', $keys)->first();
            $this->settings()->detach($key->id);
            return;
        }
        throw new NotFoundResourceException("The input type not supported");
    }


}
