<?php

namespace Singlephon\Hotification\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Installer extends Command
{
    protected $signature = 'hotification:install';

    protected $description = 'Install the Hotification';

    public function handle()
    {
        $this->info('Installing Hotification...');

        $this->info('Publishing configuration...');

        if (! $this->configExists()) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed Hotification');
    }

    private function configExists(): bool
    {
        return File::exists(config_path('hotification.php'));
    }

    private function shouldOverwriteConfig(): bool
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "Singlephon\Hotification\HotificationServiceProvider",
            '--tag' => "config"
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
