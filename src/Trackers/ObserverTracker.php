<?php

namespace Singlephon\Hotification\Trackers;

class ObserverTracker
{
    public static $observers = [];

    public static function addObserver(string $model, string $observer): void
    {
        self::$observers[$model] = $observer;
    }

    public static function hasObserver(string $model, string $observer): bool
    {
        return @ self::$observers[$model] == $observer;
    }

    public static function getObservers(string $model): array
    {
        return @ self::$observers[$model] ?? [];
    }
}
