<?php

namespace App\Services;

use App\Repositories\ApprenantFirebaseRepositoryInterface;
use App\Services\ApprenantFirebaseServiceInterface;
use Illuminate\Support\Facades\Storage;

class ApprenantFirebaseService implements ApprenantFirebaseServiceInterface
{
    protected $repository;

    public function __construct(ApprenantFirebaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createApprenant(array $data)
    {
        // Gérer le téléchargement de la photo de couverture
        if (isset($data['photo_couverture']) && $data['photo_couverture'] instanceof \Illuminate\Http\UploadedFile) {
            $photoPath = $this->uploadCoverPhoto($data['photo_couverture']);
            $data['photo_couverture'] = $photoPath;
        }

        // Structurer les données selon le schéma requis
        $structuredData = $this->structureApprenantData($data);
        
        return $this->repository->createApprenant($structuredData);
    }

    private function uploadCoverPhoto($photo)
    {
        $path = $photo->store('cover_photos', 'firebase');
        return $path;
    }

    private function structureApprenantData(array $data)
    {
        $structuredData = [
            'infos_apprenant' => [
                'nom' => $data['nom'] ?? '',
                'prenom' => $data['prenom'] ?? '',
                'email' => $data['email'] ?? '',
                'date_naissance' => $data['date_naissance'] ?? '',
                'sexe' => $data['sexe'] ?? '',
                'telephone' => $data['telephone'] ?? '',
                'adresse' => $data['adresse'] ?? '',
                'photo_couverture' => $data['photo_couverture'] ?? '',
            ],
            'presences' => [],
            'referentiel' => [
                'infos_referentiel' => [],
                'front' => [
                    'Competence 1(Maquetage et Prototypage)' => [
                        'infos_competences' => [],
                        'module1' => [
                            'infos_modules' => [],
                            'moyenne' => 0,
                            'appreciation' => '',
                            'notes' => []
                        ]
                    ]
                ],
                'back' => [
                    'Competence 2 (Analyse et Conception)' => [
                        'infos_competences' => [],
                        'module1' => [
                            'infos_modules' => [],
                            'moyenne' => 0,
                            'appreciation' => '',
                            'notes' => []
                        ]
                    ]
                ]
            ]
        ];

        if (isset($data['referentiel_id'])) {
            $structuredData['referentiel']['infos_referentiel']['id'] = $data['referentiel_id'];
        }

        return $structuredData;
    }


    public function getApprenants()
    {
        return $this->repository->getApprenants();
    }

    public function getApprenantById($id)
    {
        return $this->repository->getApprenantById($id);
    }

    public function updateApprenant($id, array $data)
    {
        // Logique métier supplémentaire si nécessaire
        return $this->repository->updateApprenant($id, $data);
    }

    public function deleteApprenant($id)
    {
        return $this->repository->deleteApprenant($id);
    }

    public function importApprenants(array $data)
    {
        // Logique métier supplémentaire si nécessaire
        return $this->repository->importApprenants($data);
    }

    public function getInactiveApprenants()
    {
        return $this->repository->getInactiveApprenants();
    }

    public function relanceApprenants(array $ids)
    {
        // Logique métier supplémentaire si nécessaire
        return $this->repository->relanceApprenants($ids);
    }

    public function relanceApprenant($id)
    {
        // Logique métier supplémentaire si nécessaire
        return $this->repository->relanceApprenant($id);
    }

    
}