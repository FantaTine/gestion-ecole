<?php

namespace App\Repositories;

interface PromotionFirebaseRepositoryInterface
{
    public function create(array $data);
    public function update(string $id, array $data);
    public function updateReferentiels(string $id, array $referentiels);
    public function updateEtat(string $id, string $etat);
    public function getAll();
    public function getEncours();
    public function cloturer(string $id);
    public function getReferentiels(string $id);
    public function getStats(string $id);
}