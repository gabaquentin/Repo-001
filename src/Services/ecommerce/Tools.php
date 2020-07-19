<?php
namespace App\Services\ecommerce;

use App\Entity\CategorieProd;
use App\Repository\CategorieProdRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Tools
{
    /**
     * @return string[]
     */
    function getTypeProduit()
    {
        return ["immobiler"=>"immobiler","fourniture"=>"fourniture"];
    }

    /**
     * recupére les erreurs d'un formulaire sous forme de table
     *
     * @param FormInterface $form
     * @return array
     */
    public function getFormErrorsTree(FormInterface $form): array
    {
        $errors = [];

        if (count($form->getErrors()) > 0) {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        } else {
            foreach ($form->all() as $child) {
                $childTree = $this->getFormErrorsTree($child);

                if (count($childTree) > 0) {
                    $errors[$child->getName()] = $childTree;
                }
            }
        }

        return $errors;
    }
}