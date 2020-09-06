<?php

namespace App\Twig;

use App\Entity\Produit;
use App\Services\ecommerce\Tools;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

Class AppExtension extends AbstractExtension
{
    private $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('dateToday', [$this, 'dateToday']),
            new TwigFunction('countProductsValid', [$this, 'countProductsValid']),
        ];
    }

    public function dateToday(\DateTime $dateTime)
    {
        $days = $dateTime->diff(new \DateTime())->d;
        if($days<1)
            return (1)." Jour";

        return ($days/365)<1?$days." Jours":((int)$days/365)." Ans";
    }

    /**
     * @param Produit[] $produits
     * @return int
     */
    public function countProductsValid($produits)
    {

        $nb = 0;
        foreach ($produits as $produit) {
            if($produit->isValide($this->tools->getDayMaxProduct()) && $produit->isVisiblite())
            {
                $nb++;
            }
        }
        return $nb;
    }
}