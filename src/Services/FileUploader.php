<?php


namespace App\Services;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory = [];
    private $slugger;
    private $fileSystem;

    public function __construct($image_produit_path, SluggerInterface $slugger,Filesystem $filesystem)
    {
        $this->targetDirectory["produit"] = $image_produit_path;
        $this->slugger = $slugger;
        $this->fileSystem = $filesystem;
    }

    /**
     * @param UploadedFile $file
     * @param string $type nom de l'indice du chemin dans la variable targetDirectory
     * @param string $imageName
     * @return string nom de l'image
     */
    public function upload(UploadedFile $file,string $type,string $imageName="")
    {
        if($imageName=="")
        {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        }
        else
        {
            $fileName = $imageName.'-'.uniqid().'.'.$file->guessExtension();
        }

        try {
            $file->move($this->getTargetDirectory()[$type], $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @param $type nom de l'indice du chemin dans la variable targetDirectory
     */
    public function remaneFile(string $oldName,string $newName,$type)
    {
        $this->getFileSystem()->rename($this->getTargetDirectory()[$type]."/".$oldName,$this->getTargetDirectory()[$type]."/".$newName);
    }

    /**
     * @param string $fileName
     * @param string $type nom de l'indice du chemin dans la variable targetDirectory
     */
    public function deleteFile(string $fileName,string $type)
    {
        $this->getFileSystem()->remove($this->getTargetDirectory()[$type]."/".$fileName);
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->fileSystem;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}