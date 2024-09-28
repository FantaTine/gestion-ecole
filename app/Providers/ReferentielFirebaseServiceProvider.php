<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ReferentielFirebaseModel;

class ReferentielFirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('referentiel-firebase', function ($app) {
            return new ReferentielFirebaseModel();
        });
    }

    public function boot()
    {
        //
    }
}