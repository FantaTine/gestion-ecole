<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PromotionFirebaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'promotion-firebase';
    }
}