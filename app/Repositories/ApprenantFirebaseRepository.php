<?php

namespace App\Repositories;

use App\Facades\ApprenantFirebaseFacade;
use App\Repositories\ApprenantFirebaseRepositoryInterface;

class ApprenantFirebaseRepository implements ApprenantFirebaseRepositoryInterface
{
    public function createApprenant(array $data)
    {
        return ApprenantFirebaseFacade::create('apprenants', $data);
    }

    public function getApprenants()
    {
        return ApprenantFirebaseFacade::getAll('apprenants');
    }

    public function getApprenantById($id)
    {
        return ApprenantFirebaseFacade::find("apprenants/{$id}");
    }

    public function updateApprenant($id, array $data)
    {
        return ApprenantFirebaseFacade::update('apprenants', $id, $data);
    }

    public function deleteApprenant($id)
    {
        return ApprenantFirebaseFacade::delete('apprenants', $id);
    }

    public function importApprenants(array $data)
    {
        // Logique pour importer plusieurs apprenants
        // Vous devrez implémenter cette méthode dans FirebaseModel
        return ApprenantFirebaseFacade::importMultiple('apprenants', $data);
    }

    public function getInactiveApprenants()
    {
        // Logique pour obtenir les apprenants inactifs
        // Vous devrez implémenter cette méthode dans FirebaseModel
        return ApprenantFirebaseFacade::getWhere('apprenants', 'active', false);
    }

    public function relanceApprenants(array $ids)
    {
        // Logique pour relancer plusieurs apprenants
        // Vous devrez implémenter cette méthode dans FirebaseModel
        return ApprenantFirebaseFacade::updateMultiple('apprenants', $ids, ['relance_sent' => true]);
    }

    public function relanceApprenant($id)
    {
        return ApprenantFirebaseFacade::update('apprenants', $id, ['relance_sent' => true]);
    }
}