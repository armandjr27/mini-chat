<?php
namespace models\base;

/**
 * Class FileService : c'est classe qui va gérer le téléchargement de fichier.
 */
class FileService extends Services
{
    /**
     * Fonction pour l'enregistrement d'un fichier dans le serveur avec vérification des entrées
     * @param mixed $file récupération des infos sur le fichier à télécharger sur le serveur
     * @return array $data[
     * 'size_error'      => "Sorry, your file is too large",
     * 'file_type_error' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed",
     * 'upload_error'    => "Sorry, your file was not uploaded.",
     * 'name'            => "file_name",
     *  ]
     */
    public function uploadFile($file = null) : array
    {
        $data = [];

        if (!$file)
        {
            $data['name'] = "";
        }
        else
        {
            $target_dir    = dirname(__DIR__) . "/public/imgs/uploads/";
            $target_file   = $target_dir . basename($file["name"]);
            $uploadOk      = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Check if file already exists
            if (file_exists($target_file)) 
            {
                if ($file["name"]) unlink($target_file);
            }
            
            // Check file size
            if ($file["size"] > 5000000) 
            {
                $data['size_error'] = "Sorry, your file is too large";
                $uploadOk           = 0;
            }
            
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) 
            {
                $data['file_type_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
                $uploadOk                = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if (!$uploadOk) 
            {
                $data['upload_error'] = "Sorry, your file was not uploaded.";
                $data['name']         = "";
            // if everything is ok, try to upload file
            } 
            else 
            {
                if (move_uploaded_file($file["tmp_name"], $target_file)) 
                {
                    $data['name'] = $this->verifyInput(strtolower(basename($file["name"])));
                } else 
                {
                    $data['upload_error'] = "Sorry, there was an error uploading your file.";
                }
            }
        }

        return $data;
    }
}