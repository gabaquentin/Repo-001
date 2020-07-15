<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=InfoLivraison::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $infoLiv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfoLiv(): ?InfoLivraison
    {
        return $this->infoLiv;
    }

    public function setInfoLiv(InfoLivraison $infoLiv): self
    {
        $this->infoLiv = $infoLiv;

        return $this;
    }
}
