<?php

namespace App\Services;

use App\Services\ReferentielFirebaseServiceInterface;
use App\Repositories\ReferentielFirebaseRepositoryInterface;

class ReferentielFirebaseService implements ReferentielFirebaseServiceInterface
{
    protected $repository;

    public function __construct(ReferentielFirebaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createReferentiel(array $data)
    {
        $data['created_at'] = now()->toDateTimeString();
        $data['updated_at'] = now()->toDateTimeString();
        $data['etat'] = 'Actif';
        
        $id = $this->repository->create($data);
        return array_merge(['id' => $id], $data);
    }

    public function getAllActiveReferentiels()
    {
        $referentiels = $this->repository->getAll();
        return array_filter($referentiels, function($referentiel) {
            return isset($referentiel['etat']) && $referentiel['etat'] === 'Actif';
        });
    }

    public function getReferentielById($id)
    {
        return $this->repository->getById($id);
    }

    public function updateReferentiel($id, array $data)
    {
        $existingReferentiel = $this->repository->getById($id);
        
        if (!$existingReferentiel) {
            throw new \Exception("Référentiel non trouvé");
        }

        $updatedData = array_merge($existingReferentiel, $data);
        $updatedData['updated_at'] = now()->toDateTimeString();

        $this->repository->update($id, $updatedData);
        
        return $this->repository->getById($id);
    }

    public function deleteReferentiel($id)
    {
        if ($this->isReferentielUsedInActivePromotion($id)) {
            throw new \Exception('Impossible d\'archiver un référentiel utilisé dans une promotion active');
        }

        return $this->repository->softDelete($id);
    }

    public function getArchivedReferentiels()
    {
        $referentiels = $this->repository->getAll();
        return array_filter($referentiels, function($referentiel) {
            return isset($referentiel['etat']) && $referentiel['etat'] === 'Archivé';
        });
    }

    public function addCompetence($referentielId, array $competenceData)
    {
        return $this->repository->addCompetence($referentielId, $competenceData);
    }

    public function addModule($referentielId, $competenceId, array $moduleData)
    {
        return $this->repository->addModule($referentielId, $competenceId, $moduleData);
    }

    public function deleteCompetence($referentielId, $competenceId)
    {
        return $this->repository->softDeleteCompetence($referentielId, $competenceId);
    }

    public function deleteModule($referentielId, $competenceId, $moduleId)
    {
        return $this->repository->deleteModule($referentielId, $competenceId, $moduleId);
    }

    private function isReferentielUsedInActivePromotion($id)
    {
        // Implement logic to check if referentiel is used in an active promotion
        // This might require querying a separate 'promotions' collection in Firebase
        // For now, we'll return false as a placeholder
        return false;
    }
}