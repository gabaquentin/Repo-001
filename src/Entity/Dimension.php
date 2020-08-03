<?php

namespace App\Entity;

use App\Repository\DimensionRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DimensionRepository::class)
 */
class Dimension
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\PositiveOrZero(message="la longueur doit être un nombre positif")
     * @ORM\Column(type="float", nullable=true)
     */
    private $longueur;

    /**
     * @Assert\PositiveOrZero(message="la largeur doit être un nombre positif")
     * @ORM\Column(type="float", nullable=true)
     */
    private $largeur;

    /**
     * @Assert\PositiveOrZero(message="la hauteur doit être un nombre positif")
     * @ORM\Column(type="float", nullable=true)
     */
    private $hauteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(?float $hauteur): self
    {
        $this->hauteur = $hauteur;

        return $this;
    }

}
