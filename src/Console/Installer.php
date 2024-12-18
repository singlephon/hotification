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

        $this->publishConfigurationFile();

        $this->createHotificationDirectory();

        $this->info('Hotification installation complete.');
    }

    private function publishConfigurationFile(): void
    {
        $this->info('Publishing configuration...');

        if (! $this->configExists()) {
            $this->publishConfiguration();
            $this->info('Configuration file published.');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration(true);
            } else {
                $this->info('Existing configuration file was not overwritten.');
            }
        }
    }

    private function createHotificationDirectory(): void
    {
        $targetPath = app_path('Hotification');

        $this->info('Creating Hotification directory in app/...');

        if (File::exists($targetPath) && $this->hasRequiredFiles($targetPath)) {
            $this->info('Hotification directory and required files already exist. Skipping creation.');

            return;
        }

        if (! File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
            $this->info('Hotification directory created.');
        }

        $this->copyObservers($targetPath);
    }

    private function copyObservers(string $targetPath): void
    {
        $filesToCopy = [
            'observers/models.stub' => 'Models.php',
            'observers/scheduled.stub' => 'Schedules.php',
        ];

        foreach ($filesToCopy as $source => $destination) {
            $destinationPath = $targetPath.'/'.$destination;

            if (File::exists($destinationPath)) {
                $this->info("File $destination already exists. Skipping.");

                continue;
            }

            $sourcePath = __DIR__.'/../../'.$source;

            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destinationPath);
                $this->info("Copied $source to $destinationPath.");
            } else {
                $this->warn("Source file $sourcePath does not exist.");
            }
        }
    }

    private function configExists(): bool
    {
        return File::exists(config_path('hotification.php'));
    }

    private function hasRequiredFiles(string $directory): bool
    {
        $requiredFiles = ['Model.php', 'Schedule.php'];

        foreach ($requiredFiles as $file) {
            if (! File::exists($directory.'/'.$file)) {
                return false;
            }
        }

        return true;
    }

    private function shouldOverwriteConfig(): bool
    {
        return $this->confirm('Config file already exists. Do you want to overwrite it?', false);
    }

    private function publishConfiguration(bool $forcePublish = false): void
    {
        $params = [
            '--provider' => "Singlephon\Hotification\HotificationServiceProvider",
            '--tag' => 'config',
        ];

        if ($forcePublish) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);
    }
}
