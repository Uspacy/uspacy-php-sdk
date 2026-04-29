<?php

namespace Uspacy\SDK;

use Illuminate\Support\ServiceProvider;

class UspacySDKServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/uspacy-sdk.php' => config_path('uspacy-sdk.php'),
        ]);
    }

    /**
     * Register the command.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/uspacy-sdk.php', 'uspacy-sdk');
    }
}
