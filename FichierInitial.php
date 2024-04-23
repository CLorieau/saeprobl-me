<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Laravel\Facades\Image;




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
        $manager = new ImageManager(Driver::class);
        // Assurez-vous que l'image existe avant de la manipuler
        if (!file_exists($this->getPath())) {
            return false;
        }

        // reading jpg image
        $image = $manager->read('images/example.jpg');

// encoding as png image
        $image->toPng()->save($cheminDestination); // Intervention\Image\EncodedImage

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
