<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

Class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('dateToday', [$this, 'dateToday']),
        ];
    }

    public function dateToday(\DateTime $dateTime)
    {
        $days = $dateTime->diff(new \DateTime())->d;
        if($days<1)
            return (1)." Jour";

        return ($days/365)<1?$days." Jours":((int)$days/365)." Ans";
    }
}