<?php


namespace App\Services\ecommerce;


use App\Entity\CategorieProd;
use App\Repository\CategorieProdRepository;

class Template
{
    private $repositoryCat;
    private $toolsServ;
    function __construct(CategorieProdRepository $repository,Tools $tools)
    {
        $this->repositoryCat = $repository;
        $this->toolsServ = $tools;
    }

    /**
     * @param CategorieProd|null $cat
     * @return string
     */
    public function categoryForm(CategorieProd $cat = null)
    {
        $nomCat = ($cat)?$cat->getNomCategorie():"";
        $data = "";
        $data .= <<<HTML
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">Nom de la catégorie</label>
            <div class="col-sm-9">
                <input type="text" value="$nomCat" class="form-control" name="nomCategorie" id="" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Type de Produit</label>
            <div class="col-sm-9">
                <select class="form-control" name="typeCategorie">
HTML;
        foreach($this->toolsServ->getTypeProduit() as $typeProduit)
        {
            $data .= <<<HTML
            <option value="$typeProduit">$typeProduit</option>
HTML;
        }

    $data .= <<<HTML
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Categorie parent</label>
            <div class="col-sm-9">
                <select class="form-control" name="categorieParent">
                <option value="">orpheline</option>
HTML;
            if($cat)
            {
                $data .= <<<HTML
                    <option selected value="{$cat->getId()}">{$cat->getNomCategorie()} </option>
HTML;
            }
                foreach($this->repositoryCat->findAllCategories() as $categorie)
                {
                    $selected = ($cat!=null&&$cat->getCategorieParent()&&$cat->getCategorieParent()->getId()==$categorie->getId())?"selected":"";
                    $data .= <<<HTML
                    <option {$selected} value="{$categorie->getId()}">{$categorie->getNomCategorie()} </option>
HTML;
                }
        $data .= <<<HTML
                </select>
            </div>
        </div>
HTML;
    return $data;
    }
}