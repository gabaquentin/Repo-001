<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Groups({"show_list"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Vous devez donner un nom au produit")
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\Choice(callback={"App\Services\ecommerce\Tools", "getTypeTransaction"}, message="Le type de transaction n'est pas défini")
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255)
     */
    private $typeTransaction;

    /**
     * @Assert\GreaterThan(value = 0,message="la valeur du prix doit être supérieure à zèro")
     * @Groups({"show_list"})
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      notInRangeMessage = "Pourcentage entre {{ min }}% et {{ max }}%",
     * )
     * @Groups({"show_list"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixPromo;

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255)
     */
    private $images = "a:0:{}";

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localisation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visiblite = true;

    /**
     * @ORM\Column(type="float")
     */
    private $priorite = 1;

    /**
     * @Groups({"show_list"})
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
    private $produitsAssocies = "a:0:{}";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $attributs = "a:0:{}";

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreConsultations = 0;

    /**
     * @ORM\OneToOne(targetEntity=Caracteristiques::class)
     */
    private $caracteristique;

    /**
     * @ORM\OneToOne(targetEntity=Dimension::class)
     */
    private $dimension;

    /**
     * @Groups({"show_list"})
     * @ORM\OneToOne(targetEntity=Date::class)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
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

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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
        $this->nom = strtolower($nom);

        return $this;
    }

    public function getTypeTransaction(): ?string
    {
        return $this->typeTransaction;
    }

    public function setTypeTransaction(string $typeTransaction): self
    {
        $this->typeTransaction = strtolower($typeTransaction);

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

    public function getImages(): ?array
    {
        return unserialize($this->images);
    }

    public function setImages(array $images): self
    {
        $this->images = serialize($images);

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

    public function isVisiblite(): ?bool
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

    public function isMeuble(): ?bool
    {
        return $this->meuble;
    }

    public function setMeuble(?bool $meuble): self
    {
        $this->meuble = $meuble;

        return $this;
    }

    public function getProduitsAssocies(): ?array
    {
        return unserialize($this->produitsAssocies);
    }

    public function setProduitsAssocies(?array $produitsAssocies): self
    {
        $this->produitsAssocies = serialize($produitsAssocies);

        return $this;
    }

    public function getAttributs(): ?array
    {
        return unserialize($this->attributs);
    }

    public function setAttributs(?array $attributs): self
    {
        $this->attributs = serialize($attributs);

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

    public function getClient(): ?User
    {
        return $this->Client;
    }

    public function setClient(?User $Client): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
