<?php

namespace App\Http\Controllers;

use App\Models\FichierInitial;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\FichierFinal;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;

// Assurez-vous d'importer la classe Image de la bonne façon

class FichierController extends Controller
{
    public function supprfile(Request $request)
    {
        $fichier = FichierInitial::find($request->id);
        if ($fichier) {
            $ok = $fichier->delete();

            if ($ok) {
                return response()->json(["status" => 1, "message" => "fichier supprimé"],201);
            } else {
                return response()->json(["status" => 0, "message" => "pb lors de la suppression"],400);
            }}
        else {
            return response()->json(["status" => 0, "Ce fichier n'existe pas"], 404);
        }
    }

    //RENOMMER UN FICHIER
    public function modif(Request $request)
    {
        $fichier = FichierInitial::find($request->id);
        if ($fichier) {
            $fichier->nomfichier = $request->nomfichier;
            $ok = $fichier->save();

            if ($ok) {
                return response()->json(["status" => 1, "message" => "fichier édité"],201);
            } else {
                return response()->json(["status" => 0, "message" => "pb lors de l'édition'"],400);
            }}
        else {
            return response()->json(["status" => 0, "Ce fichier n'existe pas"], 404);
        }
    }

    public function unfichier(Request $request)
    {
        $ok = $fichier = FichierInitial::find($request->id);

        if ($ok) {
            return response()->json($fichier);
        } else {
            return response()->json(["status" => 0, "message" => "pb lors de la récupération du produit"],400);
        }
    }

    public function listfileusers(Request $request)
    {
        $fichiers = User::with('fichiersinitial')->find($request->id); // produit where id=$request

        if ($fichiers) {
            return response()->json($fichiers);
        } else {
            return response()->json(["status" => 0, "message" => "cette utilisateur n'existe pas"],404);
        }
    }



    public function convertirImagePng(Request $request)
    {


        // Chemin de sauvegarde temporaire de l'image
        $cheminImageTemporaire = $request->file('image')->getPathName();

        // Charger l'image avec le modèle FichierInitial
        //$fichierInitial = FichierInitial::convertirEnPng($cheminImageTemporaire);

        // Chemin de sauvegarde pour l'image convertie
        $cheminImageConvertie = public_path('imgconvertie') . '/image_convertie.png';

        // Convertir l'image en format PNG et enregistrer
        //FichierInitial::convertirEnPng($cheminImageConvertie);

        
        // Assurez-vous que l'image existe avant de la manipuler
        //if (!file_exists($this->getPath())) {
          //  return false;
        //}

        // reading jpg image
        $image = Image::read($cheminImageTemporaire);

// encoding as png image
        $image->toPng()->save($cheminImageConvertie); // Intervention\Image\EncodedImage




        // Enregistrement dans la base de données
       /* $nomFichierFinal = 'image_convertie.png';
        $poidsFichierFinal = filesize($cheminImageTemporaire); // Obtenir la taille de l'image d'origine
        $extensionFichierFinal = 'png';

        // Insérer une nouvelle entrée dans la table 'fichierfinal'
        $fichierFinal = new FichierFinal();
        $fichierFinal->nomfichier = $nomFichierFinal; // Assurez-vous que 'nomfichier' est le bon nom de colonne
        $fichierFinal->poids = $poidsFichierFinal;
        $fichierFinal->extension = $extensionFichierFinal;
        $fichierFinal->save();
*/
        // Retourner une réponse ou effectuer d'autres actions nécessaires
        return response()->json(['message' => 'Image convertie en format PNG avec succès et enregistrée dans la base de données', 'chemin_image_convertie' => $cheminImageConvertie]);
    }




}
