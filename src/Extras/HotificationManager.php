<?php

namespace Singlephon\Hotification\Extras;

use Singlephon\Hotification\Observers\HotificationObserver;

class HotificationManager
{
    protected array $dynamicNotifications = [];

    public function add($modelClass, array $onCreated = [], array $onUpdated = []): self
    {
        $this->dynamicNotifications[$modelClass] = [
            'onCreated' => $onCreated,
            'onUpdated' => $onUpdated,
        ];

        $modelClass::observe(HotificationObserver::class);

        return $this;
    }

    public function getNotifications(): array
    {
        $configuredNotifications = config('hotification.models', []);
        return array_merge($configuredNotifications, $this->dynamicNotifications);
    }
}
