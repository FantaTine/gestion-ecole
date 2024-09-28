<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApprenantFirebaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'apprenant-firebase';
    }
}