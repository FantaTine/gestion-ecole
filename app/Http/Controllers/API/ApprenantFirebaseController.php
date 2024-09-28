<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ApprenantFirebaseServiceInterface;
use Illuminate\Http\Request;

class ApprenantFirebaseController extends Controller
{
    protected $service;

    public function __construct(ApprenantFirebaseServiceInterface $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'telephone' => 'required|string',
            'adresse' => 'required|string',
            'referentiel_id' => 'required|string',
            'photo_couverture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $apprenant = $this->service->createApprenant($validatedData);
        return response()->json($apprenant, 201);
    }

    public function index(Request $request)
    {
        // Gestion des filtres si nécessaire
        $apprenants = $this->service->getApprenants();
        return response()->json($apprenants);
    }

    public function show($id)
    {
        $apprenant = $this->service->getApprenantById($id);
        return response()->json($apprenant);
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            // Ajoutez vos règles de validation ici
        ]);

        $apprenant = $this->service->updateApprenant($id, $validatedData);
        return response()->json($apprenant);
    }

    public function destroy($id)
    {
        $this->service->deleteApprenant($id);
        return response()->json(null, 204);
    }

    public function import(Request $request)
    {
        // Validation du fichier Excel
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');
        // Logique pour lire le fichier Excel et extraire les données
        $data = []; // Données extraites du fichier Excel

        $result = $this->service->importApprenants($data);
        return response()->json($result);
    }

    public function inactive()
    {
        $inactiveApprenants = $this->service->getInactiveApprenants();
        return response()->json($inactiveApprenants);
    }

    public function relanceMultiple(Request $request)
    {
        $ids = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $result = $this->service->relanceApprenants($ids['ids']);
        return response()->json($result);
    }

    public function relance($id)
    {
        $result = $this->service->relanceApprenant($id);
        return response()->json($result);
    }
}