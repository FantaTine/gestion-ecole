<?php

namespace App\Repositories;

use App\Repositories\PromotionFirebaseRepositoryInterface;
use App\Facades\PromotionFirebaseFacade as Firebase;

class PromotionFirebaseRepository implements PromotionFirebaseRepositoryInterface
{
    public function create(array $data)
    {
        return Firebase::create('promotions', $data);
    }

    public function getAll()
    {
        return Firebase::getAll('promotions');
    }

    public function update(string $id, array $data)
    {
        return Firebase::update('promotions', $id, $data);
    }

    public function updateReferentiels(string $id, array $referentiels)
    {
        return Firebase::update('promotions/' . $id, 'referentiels', $referentiels);
    }

    public function updateEtat(string $id, string $etat)
    {
        return Firebase::update('promotions/' . $id, 'etat', $etat);
    }

    public function getEncours()
    {
        $promotions = Firebase::getAll('promotions');
        return collect($promotions)->where('etat', 'Actif')->first();
    }

    public function cloturer(string $id)
{
    return Firebase::update('promotions', $id, ['etat' => 'Cloturer']);
}

    public function getReferentiels(string $id)
    {
        return Firebase::find('promotions/' . $id . '/referentiels');
    }

    public function getStats(string $id)
    {
        return Firebase::find('promotions/' . $id);
    }
}