<?php

namespace StickyBeat\AudioSalad;

use Illuminate\Support\ServiceProvider;
use StickyBeat\AudioSalad\Commands\PurgeTokensCommand;
use StickyBeat\AudioSalad\Commands\DisplayTokensCommand;
use StickyBeat\AudioSalad\Commands\RefreshTokensCommand;

class AudioSaladServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . "/../config/audiosalad.php", "audiosalad");
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    $this->publishes(
      [
        __DIR__ . "/../config/audiosalad.php" => config_path("audiosalad.php"),
      ],
      "audiosalad-config"
    );

    if ($this->app->runningInConsole()) {
      $this->commands([
        DisplayTokensCommand::class,
        PurgeTokensCommand::class,
        RefreshTokensCommand::class,
      ]);
    }

    $this->commands([PurgeTokensCommand::class, RefreshTokensCommand::class]);
  }
}
