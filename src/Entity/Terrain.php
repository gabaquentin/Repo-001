<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TerrainRepository::class)
 */
class Terrain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $longeur;

    /**
     * @ORM\Column(type="float")
     */
    private $largeur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $proprietaire;

    /**
     * @ORM\OneToOne(targetEntity=Emplacement::class, mappedBy="terrain", cascade={"persist", "remove"})
     */
    private $emplacement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLongeur(): ?float
    {
        return $this->longeur;
    }

    public function setLongeur(float $longeur): self
    {
        $this->longeur = $longeur;

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

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(string $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getProprietaire(): ?Utilisateur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Utilisateur $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        // set (or unset) the owning side of the relation if necessary
        $newTerrain = null === $emplacement ? null : $this;
        if ($emplacement->getTerrain() !== $newTerrain) {
            $emplacement->setTerrain($newTerrain);
        }

        return $this;
    }
}
