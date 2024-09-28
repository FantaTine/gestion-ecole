<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ReferentielFirebaseServiceInterface;
use Illuminate\Http\Request;
use App\Rules\FirebaseUnique;

class ReferentielFirebaseController extends Controller
{
    protected $service;

    public function __construct(ReferentielFirebaseServiceInterface $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => ['required', 'string', new FirebaseUnique('referentiels', 'code')],
                'libelle' => ['required', 'string', new FirebaseUnique('referentiels', 'libelle')],
                'description' => 'required|string',
                'photo_couverture' => 'required|image|max:2048',
                'competences' => 'array'
            ]);

            $referentiel = $this->service->createReferentiel($validatedData);

            return response()->json([
                'message' => 'Référentiel créé avec succès',
                'referentiel' => $referentiel,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création du référentiel: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'code' => ['sometimes', 'required', 'string', new FirebaseUnique('referentiels', 'code', $id)],
                'libelle' => ['sometimes', 'required', 'string', new FirebaseUnique('referentiels', 'libelle', $id)],
                'description' => 'sometimes|required|string',
                'photo_couverture' => 'sometimes|required|string',
                'etat' => 'sometimes|required|in:Actif,Inactif,Archivé',
            ];

            $validatedData = $request->validate($rules);

            $referentiel = $this->service->updateReferentiel($id, $validatedData);

            return response()->json([
                'message' => 'Référentiel mis à jour avec succès',
                'referentiel' => $referentiel,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du référentiel: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->service->deleteReferentiel($id);
            return response()->json([
                'message' => 'Référentiel archivé avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'archivage du référentiel: ' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        $etat = $request->query('etat', 'Actif');
        if ($etat === 'Actif') {
            return $this->service->getAllActiveReferentiels();
        } elseif ($etat === 'Archivé') {
            return $this->service->getArchivedReferentiels();
        } else {
            return response()->json(['error' => 'État invalide'], 400);
        }
    }

    public function show($id)
    {
        return $this->service->getReferentielById($id);
    }

    public function addCompetence(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'description' => 'required|string',
                'duree_acquisition' => 'required|integer',
                'type' => 'required|in:Back-end,Front-End',
            ]);

            $competence = $this->service->addCompetence($id, $validatedData);

            return response()->json([
                'message' => 'Compétence ajoutée avec succès',
                'competence' => $competence,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de la compétence: ' . $e->getMessage()], 500);
        }
    }

    public function addModule(Request $request, $referentielId, $competenceId)
    {
        try {
            $validatedData = $request->validate([
                'nom' => 'required|string',
                'description' => 'required|string',
                'duree_acquisition' => 'required|integer',
            ]);

            $module = $this->service->addModule($referentielId, $competenceId, $validatedData);

            return response()->json([
                'message' => 'Module ajouté avec succès',
                'module' => $module,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout du module: ' . $e->getMessage()], 500);
        }
    }

    public function deleteCompetence($referentielId, $competenceId)
    {
        try {
            $this->service->deleteCompetence($referentielId, $competenceId);
            return response()->json([
                'message' => 'Compétence supprimée avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de la compétence: ' . $e->getMessage()], 500);
        }
    }

    public function deleteModule($referentielId, $competenceId, $moduleId)
    {
        try {
            $this->service->deleteModule($referentielId, $competenceId, $moduleId);
            return response()->json([
                'message' => 'Module supprimé avec succès',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du module: ' . $e->getMessage()], 500);
        }
    }
}