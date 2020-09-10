<?php
namespace App\Services\ecommerce;

use App\Entity\Caracteristiques;
use App\Entity\CategorieProd;
use App\Entity\Dimension;
use App\Repository\CategorieProdRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class Tools
{
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    /**
     * @return string[]
     */
    function getTypeProduit()
    {
        return ["immobilier"=>"immobilier","fourniture"=>"fourniture"];
    }

    public function getDefaultPack()
    {
        return [
            "id"=>0,
            "titre"=>"Default Pack",
            "description"=>"Ce pack vous deonne la possibilité de poste 5 annonces gratuitement",
            "blaz"=>"/frontend/img/illustrations/smile.svg",
            "prixBase"=>"0 F CFA",
            "nbrPostes"=>"5",
        ];
    }
    /**
     * @return string
     */
    public function getPackInfoPath()
    {
        return $this->appKernel->getProjectDir()."/src/Services/ecommerce/packinfo.json";
    }

    /**
     * @return array
     */
    function getPackInfoContent()
    {
        return json_decode(file_get_contents($this->getPackInfoPath()),true);
    }

    /**
     * @param array $data
     * @return mixed
     */
    function setPackInfoContent(array $data)
    {
        return file_put_contents($this->getPackInfoPath(),json_encode($data));
    }


    function getDayMaxProduct()
    {
        return 60;
    }

    /**
     * @return int[]
     */
    function getOrdreImage()
    {
        $tab = [];
        for ($i=0;$i<10;$i++)
            $tab[] = $i;
        return $tab;
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
                "prix*(1-(p.prixPromo/100))","desc","p"
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