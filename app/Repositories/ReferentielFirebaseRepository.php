<?php

namespace App\Repositories;

use App\Repositories\ReferentielFirebaseRepositoryInterface;
use App\Facades\ReferentielFirebaseFacade;

class ReferentielFirebaseRepository implements ReferentielFirebaseRepositoryInterface
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = ReferentielFirebaseFacade::getFacadeRoot();
    }

    public function create(array $data)
    {
        return ReferentielFirebaseFacade::create('referentiels', $data);
    }

    public function getAll()
    {
        return ReferentielFirebaseFacade::getAll('referentiels');
    }

    public function getById($id)
    {
        return ReferentielFirebaseFacade::find('referentiels', $id);
    }

    public function update($id, array $data)
    {
        return ReferentielFirebaseFacade::update('referentiels', $id, $data);
    }

    public function softDelete($id)
    {
        return ReferentielFirebaseFacade::update('referentiels', $id, ['etat' => 'Archivé']);
    }

    public function getArchived()
    {
        $referentiels = $this->getAll();
        return array_filter($referentiels, function($referentiel) {
            return isset($referentiel['etat']) && $referentiel['etat'] === 'Archivé';
        });
    }

    public function addCompetence($referentielId, array $competenceData)
    {
        return ReferentielFirebaseFacade::create("referentiels/{$referentielId}/competences", $competenceData);
    }

    public function addModule($referentielId, $competenceId, array $moduleData)
    {
        return ReferentielFirebaseFacade::create("referentiels/{$referentielId}/competences/{$competenceId}/modules", $moduleData);
    }

    public function softDeleteCompetence($referentielId, $competenceId)
    {
        return ReferentielFirebaseFacade::update("referentiels/{$referentielId}/competences", $competenceId, ['deleted_at' => now()->toDateTimeString()]);
    }

    public function deleteModule($referentielId, $competenceId, $moduleId)
    {
        return ReferentielFirebaseFacade::delete("referentiels/{$referentielId}/competences/{$competenceId}/modules", $moduleId);
    }
}