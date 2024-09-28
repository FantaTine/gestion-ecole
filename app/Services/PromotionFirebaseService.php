<?php

namespace App\Services;

use App\Services\PromotionFirebaseServiceInterface;
use App\Repositories\PromotionFirebaseRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PromotionFirebaseService implements PromotionFirebaseServiceInterface
{
    protected $repository;

    public function __construct(PromotionFirebaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createPromotion(array $data)
    {
        $validator = Validator::make($data, [
            'libelle' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required_without:duree|date|after:date_debut',
            'duree' => 'required_without:date_fin|integer',
            'referentiels' => 'array',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Vérification manuelle de l'unicité du libellé
        $existingPromotions = $this->repository->getAll();
        $libelles = array_column($existingPromotions, 'libelle');
        if (in_array($data['libelle'], $libelles)) {
            throw new ValidationException($validator, ['libelle' => ['Le libellé de la promotion doit être unique.']]);
        }

        if (!isset($data['date_fin'])) {
            $data['date_fin'] = date('Y-m-d', strtotime($data['date_debut'] . ' + ' . $data['duree'] . ' months'));
        } elseif (!isset($data['duree'])) {
            $start = new \DateTime($data['date_debut']);
            $end = new \DateTime($data['date_fin']);
            $data['duree'] = $start->diff($end)->m;
        }

        $data['etat'] = 'Inactif';

        return $this->repository->create($data);
    }

    public function updatePromotion(string $id, array $data)
    {
        // Add validation if needed
        return $this->repository->update($id, $data);
    }

    public function updatePromotionReferentiels(string $id, array $referentiels)
    {
        // Add validation if needed
        return $this->repository->updateReferentiels($id, $referentiels);
    }

    public function updatePromotionEtat(string $id, string $etat)
    {
        // Check if there's already an active promotion
        if ($etat === 'Actif') {
            $encours = $this->getPromotionEncours();
            if ($encours && $encours['id'] !== $id) {
                throw new \Exception('Il y a déjà une promotion en cours.');
            }
        }

        return $this->repository->updateEtat($id, $etat);
    }

    public function getAllPromotions()
    {
        return $this->repository->getAll();
    }

    public function getPromotionEncours()
    {
        return $this->repository->getEncours();
    }

    public function cloturerPromotion(string $id)
    {
        $promotion = $this->repository->getStats($id);
        $today = new \DateTime();
        $endDate = new \DateTime($promotion['date_fin']);
    
        if ($today < $endDate) {
            throw new \Exception('La promotion ne peut pas être clôturée avant sa date de fin.');
        }
    
        $result = $this->repository->cloturer($id);
    
        // TODO: Implement job to send notes to students
    
        return $result;
    }

    public function getPromotionReferentiels(string $id)
    {
        return $this->repository->getReferentiels($id);
    }

    public function getPromotionStats(string $id)
    {
        $promotion = $this->repository->getStats($id);
        
        if ($promotion === null) {
            return null; // ou throw new Exception('Promotion not found');
        }
    
        $allApprenants = [];
        $referentiels = $promotion['referentiels'] ?? [];
        
        foreach ($referentiels as &$referentiel) {
            $apprenants = $referentiel['apprenants'] ?? [];
            $allApprenants = array_merge($allApprenants, $apprenants);
            $referentiel['nombre_apprenants'] = count($apprenants);
        }
    
        $stats = [
            'nombre_apprenants' => count($allApprenants),
            'nombre_apprenants_actifs' => count(array_filter($allApprenants, fn($a) => $a['statut'] === 'Actif')),
            'nombre_apprenants_inactifs' => count(array_filter($allApprenants, fn($a) => $a['statut'] === 'Inactif')),
            'referentiels' => $referentiels
        ];
    
        return array_merge($promotion, $stats);
    }
}