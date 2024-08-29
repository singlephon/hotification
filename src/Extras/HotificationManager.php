<?php

namespace Singlephon\Hotification\Extras;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Singlephon\Hotification\Observers\HotificationObserver;
use Singlephon\Hotification\Trackers\ObserverTracker;

class HotificationManager
{
    protected static array $dynamicNotifications = [];

    public function add(string $model, array $onCreated = [], array $onUpdated = [], array $onDeleted = []): self
    {
        $previousNotificationState = self::$dynamicNotifications;

        self::$dynamicNotifications[$model] = [
            'onCreated' => $onCreated,
            'onUpdated' => $onUpdated,
            'onDeleted' => $onDeleted
        ];

        self::$dynamicNotifications = array_merge_recursive(self::$dynamicNotifications, $previousNotificationState);

        /** @var Model $model */
        if (! ObserverTracker::hasObserver($model, HotificationObserver::class))
        {
            $model::observe(HotificationObserver::class);
            ObserverTracker::addObserver($model, HotificationObserver::class);
        }
        return $this;
    }

    public function getNotifications(): array
    {
        $configuredNotifications = config('hotification.models', []);
        return array_merge_recursive($configuredNotifications, self::$dynamicNotifications);
    }
}
