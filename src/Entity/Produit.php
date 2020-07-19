<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeTransaction;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixPromo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localisation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visiblite;

    /**
     * @ORM\Column(type="float")
     */
    private $priorite;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dureeSejour;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $meuble;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $produitsAssocies;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $attributs;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreConsultations;

    /**
     * @ORM\ManyToOne(targetEntity=Caracteristiques::class)
     */
    private $caracteristique;

    /**
     * @ORM\ManyToOne(targetEntity=Dimension::class)
     */
    private $dimension;

    /**
     * @ORM\ManyToOne(targetEntity=Date::class)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $Client;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class)
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="produit")
     */
    private $avis;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieProd::class, inversedBy="produits")
     */
    private $categorieProd;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTypeTransaction(): ?string
    {
        return $this->typeTransaction;
    }

    public function setTypeTransaction(string $typeTransaction): self
    {
        $this->typeTransaction = $typeTransaction;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPrixPromo(): ?float
    {
        return $this->prixPromo;
    }

    public function setPrixPromo(?float $prixPromo): self
    {
        $this->prixPromo = $prixPromo;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getVisiblite(): ?bool
    {
        return $this->visiblite;
    }

    public function setVisiblite(bool $visiblite): self
    {
        $this->visiblite = $visiblite;

        return $this;
    }

    public function getPriorite(): ?float
    {
        return $this->priorite;
    }

    public function setPriorite(float $priorite): self
    {
        $this->priorite = $priorite;

        return $this;
    }

    public function getDureeSejour(): ?int
    {
        return $this->dureeSejour;
    }

    public function setDureeSejour(?int $dureeSejour): self
    {
        $this->dureeSejour = $dureeSejour;

        return $this;
    }

    public function getMeuble(): ?bool
    {
        return $this->meuble;
    }

    public function setMeuble(?bool $meuble): self
    {
        $this->meuble = $meuble;

        return $this;
    }

    public function getProduitsAssocies(): ?string
    {
        return $this->produitsAssocies;
    }

    public function setProduitsAssocies(?string $produitsAssocies): self
    {
        $this->produitsAssocies = $produitsAssocies;

        return $this;
    }

    public function getAttributs(): ?string
    {
        return $this->attributs;
    }

    public function setAttributs(?string $attributs): self
    {
        $this->attributs = $attributs;

        return $this;
    }

    public function getNbreConsultations(): ?int
    {
        return $this->nbreConsultations;
    }

    public function setNbreConsultations(int $nbreConsultations): self
    {
        $this->nbreConsultations = $nbreConsultations;

        return $this;
    }

    public function getCaracteristique(): ?Caracteristiques
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(?Caracteristiques $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getDimension(): ?Dimension
    {
        return $this->dimension;
    }

    public function setDimension(?Dimension $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getDate(): ?Date
    {
        return $this->date;
    }

    public function setDate(?Date $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->Client;
    }

    public function setClient(?Utilisateur $Client): self
    {
        $this->Client = $Client;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setProduit($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->contains($avi)) {
            $this->avis->removeElement($avi);
            // set the owning side to null (unless already changed)
            if ($avi->getProduit() === $this) {
                $avi->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorieProd(): ?CategorieProd
    {
        return $this->categorieProd;
    }

    public function setCategorieProd(?CategorieProd $categorieProd): self
    {
        $this->categorieProd = $categorieProd;

        return $this;
    }
}
