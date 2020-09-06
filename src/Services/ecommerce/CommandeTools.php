<?php


namespace App\Services\ecommerce;


class CommandeTools
{

    function getColumnsName($unset = 1)
    {
        $cols = ["id","client","livreur","dateCom","dateLivraison","modePaiement","statu"];
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
}