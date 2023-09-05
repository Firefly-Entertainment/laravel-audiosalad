<?php

namespace StickyBeat\AudioSalad\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class PurgeTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audiosalad:clear {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the audiosalad token cache file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = $this->option('force');

        if (!$force) {
            if (!$this->confirmAction()) {
                $this->components->info('Aborting...');

                return Command::SUCCESS;
            }
        }

        try {
            Redis::del('audiosalad-access-token');
            Redis::del('audiosalad-refresh-token');

        } catch (\Exception) {
            $this->components->error('Failed to clear cached tokens!');

            return Command::FAILURE;
        }

        $this->components->info('Successfully cleared cached tokens!');

        return Command::SUCCESS;
    }

    /**
     * Prompts the user for confirmation.
     *
     * @return bool
     */
    protected function confirmAction(): bool
    {
        $this->components->warn('This will clear all your cached tokens! Make sure you add a valid refresh token to your .env file to continue using the AudioSalad API.');

        return $this->confirm('Do you want to continue?');
    }
}
