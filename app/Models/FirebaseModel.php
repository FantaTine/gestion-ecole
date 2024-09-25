<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\FirebaseFacade;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use Kreait\Firebase\Auth;
class FirebaseModel 
{
    protected $database;
    protected $auth;
    protected $storage;
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials'))
            ->withDatabaseUri(config('services.firebase.database_url'));
    
        if (config('services.firebase.project_id')) {
            $factory = $factory->withProjectId(config('services.firebase.project_id'));
        }
    
        $this->database = $factory->createDatabase();
        $this->auth = $factory->createAuth();
        $this->storage = $factory->createStorage();
    }

    public function getDatabase()
    {
        return $this->database;
    }

    // Méthode pour créer une nouvelle entrée dans Firebase
    public function create($path, $data)
    {
        try {
            $reference = $this->database->getReference($path);
            $key = $reference->push()->getKey();
            $reference->getChild($key)->set($data);
            return $key;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour rechercher une entrée spécifique dans Firebase
    public function find($path)
    {
        try {
            $reference = $this->database->getReference($path);
            return $reference->getValue();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour mettre à jour une entrée spécifique dans Firebase
    public function update($path, $id, $data)
    {
        try {
            $reference = $this->database->getReference($path . '/' . $id);
            if (is_array($data)) {
                $reference->update($data);
            } else {
                $reference->set($data);
            }
            return response()->json(['success' => 'Mise à jour réussie']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour supprimer une entrée spécifique dans Firebase
    public function delete($path, $id)
    {
        try {
            $reference = $this->database->getReference($path . '/' . $id);
            $reference->remove();
            return response()->json(['success' => 'Suppression réussie']);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Méthode pour tester la connexion Firebase (optionnelle)
    public function test()
    {
        Log::info('Testing Firebase connection');
        $reference = $this->database->getReference('test');
        $reference->set([
            'date' => now()->toDateTimeString(),
            'content' => 'Firebase connection test',
        ]);
        Log::info('Data pushed to Firebase');
    }

    // Exemple d'utilisation pour stocker des données via une requête
    public function store($request)
    {
        $reference = $this->database->getReference('test'); // Remplacez 'test' par votre chemin
        $newData = $reference->push($request);
        return response()->json($newData->getValue());
    }
    // Méthode pour obtenir tous les utilisateurs depuis Firebase
    public function getAll($path)
    {
        try {
            $reference = $this->database->getReference($path);
            $users = $reference->getValue(); 

            if ($users) {
                return $users; 
            } else {
                return []; 
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs dans Firebase : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function findUserByEmail(string $email)
    {
        $users = $this->getAll('users');
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    public function findUserByPhone(string $telephone)
    {
        $users = $this->getAll('users');
        // dd($users, $telephone);
        foreach ($users as $user) {
            if (isset($userData['telephone'])) {
            if ($user['telephone'] === $telephone) {
                return $user;
            }
        }
        }
        return null;
    }
    public function createUserWithEmailAndPassword($email, $password)
    {
    // Obtenez l'instance de Firebase Auth
        try {
            $user = $this->auth->createUser(['email'=>$email, 'password'=>$password]);
            return $user->uid; // Retournez l'ID de l'utilisateur créé
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur Firebase : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur dans Firebase'], 500);
        }
    }
    public function uploadImageToStorage($filePath, $fileName)
    {
        try {
            // Récupérer le bucket de Firebase Storage
            $bucket = $this->storage->getBucket();

            // Ouvrir le fichier et le télécharger
            $file = fopen($filePath, 'r');
            $bucket->upload($file, [
                'name' => $fileName // Nom du fichier dans le bucket
            ]);

            // Obtenez l'URL de téléchargement
            $object = $bucket->object($fileName);
            $url = $object->signedUrl(new \DateTime('tomorrow')); // URL temporaire d'un jour

            Log::info('Image téléchargée avec succès sur Firebase Storage : ' . $url);
            return $url;

        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement de l\'image dans Firebase Storage : ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addCompetence($referentielId, $competenceData)
    {
        try {
            $reference = $this->database->getReference("referentiels/{$referentielId}/competences");
            $key = $reference->push()->getKey();
            $reference->getChild($key)->set($competenceData);
            return $key;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout d\'une compétence : ' . $e->getMessage());
            throw $e;
        }
    }

    // Nouvelle méthode pour ajouter un module à une compétence
    public function addModule($referentielId, $competenceId, $moduleData)
    {
        try {
            $reference = $this->database->getReference("referentiels/{$referentielId}/competences/{$competenceId}/modules");
            $key = $reference->push()->getKey();
            $reference->getChild($key)->set($moduleData);
            return $key;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout d\'un module : ' . $e->getMessage());
            throw $e;
        }
    }

    // Nouvelle méthode pour supprimer une compétence (soft delete)
    public function softDeleteCompetence($referentielId, $competenceId)
    {
        try {
            $reference = $this->database->getReference("referentiels/{$referentielId}/competences/{$competenceId}");
            $reference->update(['deleted_at' => now()->toDateTimeString()]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression douce d\'une compétence : ' . $e->getMessage());
            throw $e;
        }
    }

    // Nouvelle méthode pour supprimer un module
    public function deleteModule($referentielId, $competenceId, $moduleId)
    {
        try {
            $reference = $this->database->getReference("referentiels/{$referentielId}/competences/{$competenceId}/modules/{$moduleId}");
            $reference->remove();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'un module : ' . $e->getMessage());
            throw $e;
        }
    }

}