<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class FichierInitial extends Model
{
    use HasFactory;
    protected $table = 'fichierinitial';
    public $timestamps = false;

    /**
     * Charge une image à partir du chemin du fichier.
     *
     * @param string $chemin Le chemin du fichier de l'image.
     * @return mixed L'image chargée.
     */
    public static function make($chemin)
    {
        // Utilise Intervention Image pour charger l'image à partir du chemin du fichier
        return Image::make($chemin);
    }

    /**
     * Convertit l'image en format PNG.
     *
     * @param string $cheminDestination Le chemin de destination pour l'image convertie.
     * @return bool True si la conversion réussit, sinon False.
     */
    public function convertirEnPng($cheminDestination)
    {
        // Assurez-vous que l'image existe avant de la manipuler
        if (!file_exists($this->getPath())) {
            return false;
        }

        // Charger l'image avec Intervention Image
        $image = Image::make($this->getPath());

        // Convertir l'image en format PNG et enregistrer
        $image->encode('png')->save($cheminDestination);

        return true;
    }

    /**
     * Obtient le chemin complet du fichier d'image.
     *
     * @return string Le chemin complet du fichier d'image.
     */
    public function getPath()
    {
        return public_path('files' . $this->chemin); // Assurez-vous que 'chemin' est le bon nom de colonne
    }

    // Définissez d'autres relations ou méthodes nécessaires ici
}
