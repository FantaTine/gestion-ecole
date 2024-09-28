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
        $credentials=base64_decode("ewogICJ0eXBlIjogInNlcnZpY2VfYWNjb3VudCIsCiAgInByb2plY3RfaWQiOiAiZ2VzdGlvbi1l
        Y29sZS1kYmI0OCIsCiAgInByaXZhdGVfa2V5X2lkIjogIjJiYTJlMWNjNzI1NDY4Zjk4Y2Y3ZTU5
        YTNjN2Q3MWQ0OWYwZGQ4ZmIiLAogICJwcml2YXRlX2tleSI6ICItLS0tLUJFR0lOIFBSSVZBVEUg
        S0VZLS0tLS1cbk1JSUV2UUlCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktjd2dnU2pBZ0VBQW9J
        QkFRRElrdk1PeWdreVByQzVcbm9iUlA1WVVRMFBBaU80RHZqYTZWS3IzY1RQYmRxalFUQkJHQk5a
        QjBsWCt2RU1ib1Vpc2dEUmFZQWJaamNvWHNcbm5nN21JTFJLbXIzYXJDTlNnTFEyY0R3QVIraG4r
        RVBJMkQ1TVBjTE1DNEF0ckdVVjg3VHNyWHYyZ2JERHNXK3Zcbko0VVh6MEdqbkY4aEJ0T0JEWEhn
        Q3A2WDhHNXFEV08vYlQveXFlaVF3UkpzdnhIQVJGeFNIRWRheE1saU8wY3pcblZRZGt5ZGMwc1Fy
        NW52aFhWZ25QTitDUTEwWW5KT3FseCtzRU1KdVlieXNVMEVzd216dXFQalBmVGlYV0lQdnJcbjR4
        a0xNbDJDZDN0RW4rMlZjSVdtbVlXWUVPSkdMK2RVZGtCWEh6VzlmVlZXT2ZBZUN3cjFsY3ZGZjZY
        ZnZQekNcbnU5TnJRK3QvQWdNQkFBRUNnZ0VBVFpiSy80V1lXUjkzTGRnbjhHQlJRVmdTREt3SURL
        TTZUZW5pMkQzTm9hSWRcbjB0MVlDQ3U0WGVFWEs1bkoyRVV3K1kwV21HYlJMMWMyQjNwOU9QaWR4
        ZDVtWXhrRDhQNW91RVppYjVsaGhoYTdcbjRnNEV5Mmw0MllYNVo3R3d5UFVsb0FqY1UxZUlFYXVx
        RUkreEpLekFMNUptR2xpUnFQS1NncUNaQ1IyeEdNUzFcbjJEU25HTSt4QS81QnJiWjM1Tmd2K0kr
        OExJaU8wTk1lZ2R0Y0JwclYyZ2N2WUh4ajRZK0RuVklzalh5blE0cG9cbitUaVRJNXdoa2xhT2pB
        M1dOTXBYN2Fkc2kzOVRBeENpdEpPTG9Fb200TEhyMWVnc3RNMG9SdzU1L2xpSUZaL01cbmZxaDdR
        TlhaZE1sdnpCdldCYzE4TUphaUIyaW1ybU0rM3VzeWJ0eVp3UUtCZ1FEbzFWMElNazRETmdSdGNR
        Rk9cbjlySzVvcnhvWG1KcmRKYUE4WlRLNGk2eHhISHpyWmZrN1hHRFF0OEhEcnNpR3lYNEdpWGxJ
        eUV0WDg5SHhYK25cblNvTEJtbkFtTFljUm4wQmpXTHJacU42VVZvU3lsSTFoalBCa0YvaTZwNlRO
        NHVyM3ZLbmt6TnpWSThCQ2drRW9cbkpSY3hoeW9SQ0Z4UEt5WEt3bU5tbFQ0ZVRRS0JnUURjaCtN
        bDdJOHNwZlBzV3ViU2xsb1cxOFROSzZ6UWkza29cbm1Zcnc5V2phdndvVlhJek94T1orTGNYakgv
        Sm90Y2l5WGc1TzhmT0NHL0lJN1pyR205blNtWGNIU0hxWHlQbm5cblNRUTNiYjExTmJjS05FWGwr
        WWx6V091V09GKzhXbmNVbTJJYjgyMVVuanV4d0l3Ylo0SXJDb3VJQnZJOS91UWdcbnhkYURXb1FP
        K3dLQmdCalA1QUlqbWp5dFhQdHN0Mzc2dTBFZEhvYmd5QXc3ZUc1MVVDM3V2SnNsSEIranVEUWxc
        bk9adUxYTWtid01WNXJ5b3BYekJ6OWUwbjhLYnRYUlV1MDVCZUxPVmtwS2cwR0dCOTdDdVYyMkpq
        ZGZDeDgvOFpcblRlVGN5UnpiRUswQUtab00rTzN4QnZlUHY4VldyQ3JqQ1AyMk1iMXU3cWRoS2Iz
        WnlVMzlTWWxoQW9HQU5BYW1cbjB0cVZDRjU2UmlkSHp5U0RTbUpLM25CUVM3NnJyYVZUZnZjV2Jv
        eWxMMWJ2TFJTTW4vWGllbXVkLzQvck1oRTRcblpPMTFtaHRIcFUyUXR3d3JmaUNRVVJxTE9XWERk
        d25sd2NIVThXTGp0TGVTU2VmdHdsV1c5cENFSFdabU0ybFlcbjR1a3h3TVczcFg2Y1R4YkRRYTR5
        THJ3Y3gvMXJDa1JDRUNqNWdLTUNnWUVBMFJLbVBFdGR0aUJ1QU9zYXludjFcbmVzTDJuSk9hdjV2
        SE5JNkdENTRiU2lpaU9BZ3ByVlhIODJNVjBERnA3N3FYMDhzeWRBNDFZQlJXNjhCSjRLd1BcbnNR
        TWtWa3NROFJxQndZMHBZRkF4YWc4MXVtRE1jNm1jL0RCNWxEUHBmbWRiKzRralZQT0d6aFJ0OVlN
        b09hdThcbk10VDNKY0hjcjBpR0t1QTdDV09Pb2RnPVxuLS0tLS1FTkQgUFJJVkFURSBLRVktLS0t
        LVxuIiwKICAiY2xpZW50X2VtYWlsIjogImZpcmViYXNlLWFkbWluc2RrLThrZjczQGdlc3Rpb24t
        ZWNvbGUtZGJiNDguaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLAogICJjbGllbnRfaWQiOiAiMTAw
        MTk5Mzg3OTUxNjI1NTk2NzQxIiwKICAiYXV0aF91cmkiOiAiaHR0cHM6Ly9hY2NvdW50cy5nb29n
        bGUuY29tL28vb2F1dGgyL2F1dGgiLAogICJ0b2tlbl91cmkiOiAiaHR0cHM6Ly9vYXV0aDIuZ29v
        Z2xlYXBpcy5jb20vdG9rZW4iLAogICJhdXRoX3Byb3ZpZGVyX3g1MDlfY2VydF91cmwiOiAiaHR0
        cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vb2F1dGgyL3YxL2NlcnRzIiwKICAiY2xpZW50X3g1MDlf
        Y2VydF91cmwiOiAiaHR0cHM6Ly93d3cuZ29vZ2xlYXBpcy5jb20vcm9ib3QvdjEvbWV0YWRhdGEv
        eDUwOS9maXJlYmFzZS1hZG1pbnNkay04a2Y3MyU0MGdlc3Rpb24tZWNvbGUtZGJiNDguaWFtLmdz
        ZXJ2aWNlYWNjb3VudC5jb20iLAogICJ1bml2ZXJzZV9kb21haW4iOiAiZ29vZ2xlYXBpcy5jb20i
        Cn0=");
        $factory = (new Factory)
            ->withServiceAccount(json_encode($credentials, true))
            ->withDatabaseUri("https://gestion-ecole-dbb48-default-rtdb.firebaseio.com/");
    
        if (config('services.firebase.project_id')) {
            $factory = $factory->withProjectId("gestion-ecole-dbb48");
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