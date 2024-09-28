<?php

namespace App\Services;

interface PromotionFirebaseServiceInterface
{
    public function createPromotion(array $data);
    public function updatePromotion(string $id, array $data);
    public function updatePromotionReferentiels(string $id, array $referentiels);
    public function updatePromotionEtat(string $id, string $etat);
    public function getAllPromotions();
    public function getPromotionEncours();
    public function cloturerPromotion(string $id);
    public function getPromotionReferentiels(string $id);
    public function getPromotionStats(string $id);
}