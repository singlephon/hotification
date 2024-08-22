<?php

namespace Singlephon\Hotification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Singlephon\Hotification\Extras\HotificationManager;
use Singlephon\Hotification\Extras\HotificationSender;
use Singlephon\Hotification\Interfaces\HotificationSenderInterface;

class Hotification
{
    /**
     * @param Model|Collection $receivers
     * @return HotificationSenderInterface|HotificationSender
     */
    public function notify(Model|Collection $receivers): HotificationSenderInterface|HotificationSender
    {
        $senderClass = config('hotification.extras.hotification_sender');

        return (new $senderClass())->to($receivers);
    }

    public function manager(): HotificationManager
    {
        return new HotificationManager();
    }
}
