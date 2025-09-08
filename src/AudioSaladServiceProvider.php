<?php

namespace Firefly\AudioSalad;

use Illuminate\Support\ServiceProvider;
use Firefly\AudioSalad\Commands\PurgeTokensCommand;
use Firefly\AudioSalad\Commands\DisplayTokensCommand;
use Firefly\AudioSalad\Commands\RefreshTokensCommand;

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
