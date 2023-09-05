<?php

namespace StickyBeat\AudioSalad\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class DisplayTokensCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = "audiosalad:tokens";

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Display information about AudioSalad tokens";

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $this->components->info(
      "Access token: " . Redis::get("audiosalad-access-token")
    );

    $this->components->info(
      "Refresh token: " . Redis::get("audiosalad-refresh-token")
    );

    return Command::SUCCESS;
  }
}
