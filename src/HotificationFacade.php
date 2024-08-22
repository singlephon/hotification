<?php

namespace Singlephon\Hotification;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Singlephon\Hotification\Hotification
 */
class HotificationFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hotification';
    }
}
