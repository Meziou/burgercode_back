<?php

namespace App\Service;
use Intervention\Image\ImageManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Uploader {

    public function __construct(private ParameterBagInterface $params) {}

    public function upload(string $file):string {
        $pathToUploads = $this->params->get('kernel.project_dir') . '/public/uploads/';
        if(!is_dir($pathToUploads.'thumbnails')) {
            mkdir($pathToUploads.'thumbnails', 0777, true);
        }
        

        $filename = uniqid() . '.jpg';
        $manager = new ImageManager();
        $img = $manager->make($file);


        $img->save($pathToUploads . $filename);
        $img->crop(200,140)->save($pathToUploads.'thumbnails/'.$filename);

        return $filename;
    }
}