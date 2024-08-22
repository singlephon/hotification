<?php

namespace Singlephon\Hotification\Notifications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

abstract class AbstractHotification extends Notification
{
    /**
     * List of recievers
     *
     * @return Model|Collection|array
     */
    abstract public function receivers(): Model|array|Collection;
}
