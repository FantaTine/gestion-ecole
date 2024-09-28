<?php

namespace App\Providers;

use App\Models\ApprenantFirebaseModel;
use App\Models\PromotionFirebaseModel;
use App\Models\ReferentielFirebaseModel;
use App\Models\User;
use App\Models\UserFirebaseModel;
use App\Repositories\ApprenantFirebaseRepository;
use App\Repositories\ApprenantFirebaseRepositoryInterface;
use App\Repositories\PromotionFirebaseRepository;
use App\Repositories\PromotionFirebaseRepositoryInterface;
use App\Repositories\ReferentielFirebaseRepository;
use App\Services\ReferentielFirebaseService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserFirebaseRepositoryInterface;
use App\Repositories\UserFirebaseRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserFirebaseServiceInterface;
use App\Services\UserFirebaseService;
use App\Services\AuthFirebaseServiceInterface;
use App\Services\AuthFirebaseService;
use App\Repositories\ReferentielFirebaseRepositoryInterface;
use App\Services\ApprenantFirebaseService;
use App\Services\ApprenantFirebaseServiceInterface;
use App\Services\PromotionFirebaseService;
use App\Services\PromotionFirebaseServiceInterface;
use App\Services\ReferentielFirebaseServiceInterface;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
      
        $this->app->singleton('user_firebase', function () {
            return new UserFirebaseModel();
        });
        $this->app->bind(UserFirebaseRepositoryInterface::class, UserFirebaseRepository::class);
        $this->app->bind(UserFirebaseServiceInterface::class, UserFirebaseService::class);
        $this->app->bind(AuthFirebaseServiceInterface::class, AuthFirebaseService::class);

        $this->app->bind('referentiel-firebase', function ($app) {
            return new ReferentielFirebaseModel();
        });
        $this->app->bind(ReferentielFirebaseRepositoryInterface::class, ReferentielFirebaseRepository::class);
        $this->app->bind(ReferentielFirebaseServiceInterface::class, ReferentielFirebaseService::class);

        $this->app->bind('promotion-firebase', function ($app) {
            return new PromotionFirebaseModel();
        });
        $this->app->bind(PromotionFirebaseRepositoryInterface::class, PromotionFirebaseRepository::class);
        $this->app->bind(PromotionFirebaseServiceInterface::class, PromotionFirebaseService::class);

        $this->app->bind(ApprenantFirebaseRepositoryInterface::class, ApprenantFirebaseRepository::class);
    $this->app->bind(ApprenantFirebaseServiceInterface::class, ApprenantFirebaseService::class);

    $this->app->bind('apprenant-firebase', function ($app) {
        return new ApprenantFirebaseModel();
    });
  

    }

    public function boot(): void
    {
        //
    }
}
