<?php

namespace StickyBeat\AudioSalad;

use Illuminate\Support\ServiceProvider;

class AudioSaladServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/audiosalad.php', 'audiosalad'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/audiosalad.php' => config_path('audiosalad.php'),
        ], 'audiosalad-config');
    }
}
