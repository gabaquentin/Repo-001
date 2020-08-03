<?php

namespace App\Entity;

use App\Repository\CaracteristiquesRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CaracteristiquesRepository::class)
 */
class Caracteristiques
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\PositiveOrZero(message="le nbre de chambres doit être un nombre positif")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreChambres;

    /**
     * @Assert\PositiveOrZero(message="le nbre de salle de bain doit être un nombre positif")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreSalleBain;

    /**
     * @Assert\PositiveOrZero(message="le nbre de parking doit être un nombre positif")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreParking;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbreChambres(): ?int
    {
        return $this->nbreChambres;
    }

    public function setNbreChambres(?int $nbreChambres): self
    {
        $this->nbreChambres = $nbreChambres;

        return $this;
    }

    public function getNbreSalleBain(): ?int
    {
        return $this->nbreSalleBain;
    }

    public function setNbreSalleBain(?int $nbreSalleBain): self
    {
        $this->nbreSalleBain = $nbreSalleBain;

        return $this;
    }

    public function getNbreParking(): ?int
    {
        return $this->nbreParking;
    }

    public function setNbreParking(?int $nbreParking): self
    {
        $this->nbreParking = $nbreParking;

        return $this;
    }

}
