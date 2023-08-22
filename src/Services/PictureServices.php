<?php

namespace App\Service;

use Exception;

use function PHPUnit\Framework\fileExists;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureServices
{
    private $params;

    public function __construct(ParameterBagInterface $params) {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = "", ?int $width = 250, ?int $height = 250)
    {
        // On donne un nvx nom à l'image
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        // On recupère les infos de l'image 
        $picture_infos = getimagesize($picture);

        if($picture_infos === false) {
            // throw new Exception('Format d\'image incorrect');
        }

        // On verifie le format de l'image
        switch($picture_infos['mime']) {
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            // default: 
                // throw new Exception('Format d\'image incorrect');
        }


        // On recadre l'image
        $image_width = $picture_infos[0];
        $image_height = $picture_infos[1];

        // On verifie l'orientation
        switch ($image_width <=> $image_height) {
            case -1: //portrait
                $squareSize = $image_width;
                $src_x = 0;
                $src_y = ($image_height - $squareSize) / 2;
                break;
            case 0: //carré
                $squareSize = $image_width;
                $src_x = 0;
                $src_y = 0;
                break;
            case 1: //paysage
                $squareSize = $image_width;
                $src_x = ($image_width - $squareSize) / 2;
                $src_y = 0;
                break;
        }

        // On créé une nouvelle img "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width ,$height, $squareSize, $squareSize);

        // Créer la route
        $path = $this->params->get('images_directory') . $folder;

        //On créer le dossier de destination si il n'existe pas
        if(!file_exists($path . '/mini/')){
            mkdir($path . '/mini/', 0755, true);
        }

        //On stock l'image recadrée
        imagewebp($resized_picture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);

        $picture->move($path . '/', $fichier);

        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = "", ?int $width = 250, ?int $height = 250)
    {
        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;
            
            if(file_exists($mini)) {
                unlink($mini);
                $success = true;
            }
            
            $original = $path . '/' . $fichier;
            
            if(file_exists($original)) {
                unlink($mini);
                $success = true;
            }

            return $success;
        }
        return false;
    }
}