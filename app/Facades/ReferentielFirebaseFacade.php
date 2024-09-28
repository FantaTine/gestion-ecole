<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ReferentielFirebaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'referentiel-firebase';
    }
}