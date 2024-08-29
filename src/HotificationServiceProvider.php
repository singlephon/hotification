<?php

namespace Singlephon\Hotification;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Singlephon\Hotification\Console\HotificationHandler;
use Singlephon\Hotification\Console\Installer;
use Singlephon\Hotification\Extras\HotificationManager;
use Singlephon\Hotification\Notifications\AbstractHotification;
use Singlephon\Hotification\Observers\HotificationObserver;
use Singlephon\Hotification\Trackers\ObserverTracker;

class HotificationServiceProvider extends ServiceProvider
{


    public function observers(): void
    {
        $modelArray = config('hotification.models');

        foreach ($modelArray as $modelClass => $events) {
            /** @var Model $modelClass */
            if (ObserverTracker::hasObserver($modelClass, HotificationObserver::class))
                continue;

            $modelClass::observe(HotificationObserver::class);
            ObserverTracker::addObserver($modelClass, HotificationObserver::class);
        }
    }

    public function scheduledNotifications(Schedule $schedule): void
    {
        $notifications = config('hotification.scheduled_notifications');

        foreach ($notifications as $name => $notificationConfig) {
            if (is_callable($notificationConfig)) {

                call_user_func($notificationConfig, $schedule);
            } elseif (is_array($notificationConfig)) {

                $events = $notificationConfig['events'] ?? [];
                $cronExpression = $notificationConfig['schedule'] ?? null;

                if ($cronExpression) {
                    $schedule->call(function () use ($events) {
                        foreach ($events as $event) {
                            if (is_callable($event)) {
                                call_user_func($event);
                            } elseif (class_exists($event)) {
                                $notification = new $event();
                                $this->sendScheduledNotification($notification);
                            }
                        }
                    })->cron($cronExpression);
                }
            }
        }
    }

    protected function sendScheduledNotification($notification): void
    {
        if ($notification instanceof AbstractHotification) {
            $receivers = $notification->receivers();

            if ($receivers instanceof \Illuminate\Database\Eloquent\Model) {
                $receivers = collect([$receivers]);
            } elseif (is_array($receivers)) {
                $receivers = collect($receivers);
            }

            foreach ($receivers as $receiver) {
                $receiver->notify($notification);
            }
        } else {
            throw new \Exception('Given ' .  $notification::class . ' must extend AbstractHotification.');
        }
    }

    /**
     * Bootstrap the application services.
     * @throws BindingResolutionException
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('hotification.php'),
            ], 'config');

            $this->commands([
                Installer::class,
                HotificationHandler::class
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $this->scheduledNotifications($schedule);
            });
        }
        $this->observers();
    }

    /**
     * Register the application services.
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'hotification');

        $this->mergeLoggingChannels();

        $this->app->singleton('hotification', function () {
            return new Hotification;
        });
    }

    /**
     * @throws BindingResolutionException
     */
    private function mergeLoggingChannels(): void
    {
        $packageLoggingConfig = require __DIR__ . '/../config/logging.php';

        $config = $this->app->make('config');

        $config->set('logging.channels', array_merge(
            $packageLoggingConfig['channels'] ?? [],
            $config->get('logging.channels', [])
        ));
    }
}
