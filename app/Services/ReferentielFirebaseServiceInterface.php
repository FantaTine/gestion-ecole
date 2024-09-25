<?php

namespace App\Services;

interface ReferentielFirebaseServiceInterface
{
    public function createReferentiel(array $data);
    public function getAllActiveReferentiels();
    public function getReferentielById($id);
    public function updateReferentiel($id, array $data);
    public function deleteReferentiel($id);
    public function getArchivedReferentiels();
    public function addCompetence($referentielId, array $competenceData);
    public function addModule($referentielId, $competenceId, array $moduleData);
    public function deleteCompetence($referentielId, $competenceId);
    public function deleteModule($referentielId, $competenceId, $moduleId);
}