<?php

namespace Aldeebhasan\MultiAgents;

use Aldeebhasan\MultiAgents\Models\Device;
use Aldeebhasan\MultiAgents\Observers\DeviceObserver;
use Illuminate\Support\ServiceProvider;

class MultiAgentsServiceProvider extends ServiceProvider
{
    public function boot(){
        //load resources
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/multi_agent.php' => config_path('multi_agent.php'),
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ],'multi-agents');

        //register observers
        Device::observe(DeviceObserver::class);
    }
    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/../config/multi_agent.php', 'multi_agent'
        );
    }


}
