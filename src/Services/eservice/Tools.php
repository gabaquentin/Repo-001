<?php

namespace App\Services\eservice;

use App\Entity\Populaire;
use App\Repository\PopulaireRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class Tools
{

    function getColumnsName($unset = 1)
    {
        $cols = ["id","images","titre","lien","date"];
        if($unset!=null)
            unset($cols[$unset]);

        return array_values($cols);
    }
    /**
     * @param int $col
     * @return string
     */
    function getColumnName(int $col)
    {
        $cols = $this->getColumnsName(null);
        return (array_key_exists($col,$cols))?$cols[$col]:$cols[2];
    }

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