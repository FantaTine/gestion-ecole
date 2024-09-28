<?php

namespace App\Repositories;

interface ReferentielFirebaseRepositoryInterface
{
    public function create(array $data);
    public function getAll();
    public function getById($id);
    public function update($id, array $data);
    public function softDelete($id);
    public function getArchived();
    public function addCompetence($referentielId, array $competenceData);
    public function addModule($referentielId, $competenceId, array $moduleData);
    public function softDeleteCompetence($referentielId, $competenceId);
    public function deleteModule($referentielId, $competenceId, $moduleId);
}