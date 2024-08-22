<?php

namespace Singlephon\Hotification\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Singlephon\Hotification\Extras\HotificationManager;
use Singlephon\Hotification\Hotification;
use Singlephon\Hotification\Notifications\AbstractHotification;
use Throwable;

class HotificationObserver
{
    protected HotificationManager $manager;

    public function __construct(HotificationManager $manager)
    {
        $this->manager = $manager;
    }

    public function created(Model $model)
    {
        $this->handleEvent($model, 'onCreated');
    }

    public function updated(Model $model)
    {
        $this->handleEvent($model, 'onUpdated');
    }

    public function deleted(Model $model)
    {
        $this->handleEvent($model, 'onDeleted');
    }

    /**
     * @throws \Exception
     */
    protected function handleEvent(Model $model, string $event): void
    {
        $notifications = $this->manager->getNotifications();
        $modelClass = get_class($model);

        if (isset($notifications[$modelClass][$event])) {
            foreach ($notifications[$modelClass][$event] as $handler) {
                try {
                    if (is_callable($handler)) {

                        call_user_func($handler, $model);
                    } elseif (class_exists($handler)) {

                        $notification = new $handler($model);
                        $this->sendNotification($notification);
                    }
                } catch (Throwable $e) {
                    Log::channel('hotification')->error("Error while sending notification to {$modelClass} for {$event} event: " . $e->getMessage(), [
                        'model' => $modelClass,
                        'event' => $event,
                    ]);
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    protected function sendNotification($notification): void
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
}
