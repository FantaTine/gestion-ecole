<?php

namespace App\Repositories;

interface ApprenantFirebaseRepositoryInterface
{
    public function createApprenant(array $data);
    public function getApprenants();
    public function getApprenantById($id);
    public function updateApprenant($id, array $data);
    public function deleteApprenant($id);
    public function importApprenants(array $data);
    public function getInactiveApprenants();
    public function relanceApprenants(array $ids);
    public function relanceApprenant($id);
}