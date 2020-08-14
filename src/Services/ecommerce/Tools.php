<?php
namespace App\Services\ecommerce;

use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Dimension;
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
        return ["immobilier"=>"immobilier","fourniture"=>"fourniture"];
    }

    /**
     * @return int[]
     */
    function getOrdreImage()
    {
        return [0,1,2,3];
    }

    /**
     * @param string $val
     * @return string[]
     */
    function getOrderColumnProd($val)
    {
        $tab = [
            "Plus récent"=>[
                "dateModification","desc","d"
            ],
            "Prix élévé"=>[
                "prix","desc","p"
            ],
            "Prix bas"=>[
                "prix","asc","p"
            ],
            "Pertinance"=>[
                "nbreConsultations","desc","p"
            ],
        ];

        return array_key_exists($val,$tab)?$tab[$val]:$tab["Plus récent"];
    }

    function getColumnsName($unset = 1)
    {
        $cols = ["id","images","nom","typeTransaction","prix","prixPromo","localisation"];
        if($unset!=null)
            unset($cols[$unset]);

        return array_values($cols);
    }
    /**
     * @param int $col
     * @return string
     */
    function getColumnName($col)
    {
        $cols = $this->getColumnsName(null);
        return (array_key_exists($col,$cols))?$cols[$col]:$cols[2];
    }

    /**
     * @return string[]
     */
    function getTypeTransaction()
    {
        return ["vente"=>"vente","location"=>"location"];
    }

    /**
     * @param Caracteristiques|null $caracteristiques
     * @return bool
     */
    public function isCaracteristiquesPersistable($caracteristiques)
    {
        if($caracteristiques==null)
            return false;

        return ($caracteristiques->getNbreChambres()!=null || $caracteristiques->getNbreParking()!=null || $caracteristiques->getNbreSalleBain()!=null);
    }

    /**
     * @param Dimension|null $dimension
     * @return bool
     */
    public function isDimensionPersistable($dimension)
    {
        if($dimension==null)
            return false;

        return ($dimension->getLargeur()!=null || $dimension->getLongueur()!=null || $dimension->getHauteur()!=null);
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