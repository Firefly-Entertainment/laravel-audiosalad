<?php

namespace StickyBeat\AudioSalad\Facades;

use Illuminate\Support\Facades\Facade;

class AudioSalad extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \StickyBeat\AudioSalad\Services\AudioSalad::class;
    }
}
