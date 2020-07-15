<?php

namespace App\Entity;

use App\Repository\CategorieProdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieProdRepository::class)
 */
class CategorieProd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomCategorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeCategorie;

    /**
     * @ORM\OneToMany(targetEntity=SousCategorieProd::class, mappedBy="categorie")
     */
    private $sousCategorieProds;

    public function __construct()
    {
        $this->sousCategorieProds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function getTypeCategorie(): ?string
    {
        return $this->typeCategorie;
    }

    public function setTypeCategorie(string $typeCategorie): self
    {
        $this->typeCategorie = $typeCategorie;

        return $this;
    }

    /**
     * @return Collection|SousCategorieProd[]
     */
    public function getSousCategorieProds(): Collection
    {
        return $this->sousCategorieProds;
    }

    public function addSousCategorieProd(SousCategorieProd $sousCategorieProd): self
    {
        if (!$this->sousCategorieProds->contains($sousCategorieProd)) {
            $this->sousCategorieProds[] = $sousCategorieProd;
            $sousCategorieProd->setCategorie($this);
        }

        return $this;
    }

    public function removeSousCategorieProd(SousCategorieProd $sousCategorieProd): self
    {
        if ($this->sousCategorieProds->contains($sousCategorieProd)) {
            $this->sousCategorieProds->removeElement($sousCategorieProd);
            // set the owning side to null (unless already changed)
            if ($sousCategorieProd->getCategorie() === $this) {
                $sousCategorieProd->setCategorie(null);
            }
        }

        return $this;
    }
}
