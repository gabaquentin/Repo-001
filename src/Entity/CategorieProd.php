<?php

namespace App\Entity;

use App\Repository\CategorieProdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\NotBlank(message="Vous devez donner un nom de catégorie")
     * @ORM\Column(type="string", length=255)
     */
    private $nomCategorie;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $typeCategorie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieProd::class, inversedBy="sousCategories")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $categorieParent;

    /**
     * @ORM\OneToMany(targetEntity=CategorieProd::class, mappedBy="categorieParent")
     */
    private $sousCategories;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @Assert\GreaterThan(value = -1,message="la valeur du prix doit être supérieure à zèro")
     * @Assert\NotBlank(message="Vous devez donner une valeur pour une unité d'annonce")
     * @ORM\Column(type="float", nullable=true)
     */
    private $uniteAnnonce;

    /**
     * @Assert\GreaterThan(value = -1,message="la valeur du prix doit être supérieure à zèro")
     * @Assert\NotBlank(message="Vous devez donner un prix pour une unité de boost")
     * @ORM\Column(type="float", nullable=true)
     */
    private $uniteBoost;


    public function __construct()
    {
        $this->sousCategories = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(?string $nomCategorie): self
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCategorieParent(): ?self
    {
        return $this->categorieParent;
    }

    public function setCategorieParent(?self $categorieParent): self
    {
        $this->categorieParent = $categorieParent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(self $sousCategory): self
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories[] = $sousCategory;
            $sousCategory->setCategorieParent($this);
        }

        return $this;
    }

    public function removeSousCategory(self $sousCategory): self
    {
        if ($this->sousCategories->contains($sousCategory)) {
            $this->sousCategories->removeElement($sousCategory);
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategorieParent() === $this) {
                $sousCategory->setCategorieParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    /*public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorieProd($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getCategorieProd() === $this) {
                $produit->setCategorieProd(null);
            }
        }

        return $this;
    }*/

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getUniteAnnonce(): ?float
    {
        return $this->uniteAnnonce;
    }

    public function setUniteAnnonce(?float $uniteAnnonce): self
    {
        $this->uniteAnnonce = $uniteAnnonce;

        return $this;
    }

    public function getUniteBoost(): ?float
    {
        return $this->uniteBoost;
    }

    public function setUniteBoost(?float $uniteBoost): self
    {
        $this->uniteBoost = $uniteBoost;

        return $this;
    }

}
