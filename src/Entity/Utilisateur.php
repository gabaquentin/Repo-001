<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePartenaire;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="client")
     */
    private $reclamations;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="administrateur")
     */
    private $reclamationsAdmin;

    /**
     * @ORM\OneToMany(targetEntity=Terrain::class, mappedBy="proprietaire")
     */
    private $terrains;

    /**
     * @ORM\OneToMany(targetEntity=Boutique::class, mappedBy="propritaire")
     */
    private $boutiques;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="Client")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="client")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="livreur")
     */
    private $commnadesLivreur;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="client")
     */
    private $avis;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="prestataire")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="client")
     */
    private $demandes;

    /**
     * @ORM\OneToOne(targetEntity=ExtraInfo::class, cascade={"persist", "remove"})
     */
    private $extraInfo;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, cascade={"persist", "remove"})
     */
    private $infoUser;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="utilisateur")
     */
    private $chats;


    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->reclamationsAdmin = new ArrayCollection();
        $this->terrains = new ArrayCollection();
        $this->boutiques = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->commnadesLivreur = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getTypePartenaire(): ?string
    {
        return $this->typePartenaire;
    }

    public function setTypePartenaire(?string $typePartenaire): self
    {
        $this->typePartenaire = $typePartenaire;

        return $this;
    }

    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setClient($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->contains($reclamation)) {
            $this->reclamations->removeElement($reclamation);
            // set the owning side to null (unless already changed)
            if ($reclamation->getClient() === $this) {
                $reclamation->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamationsAdmin(): Collection
    {
        return $this->reclamationsAdmin;
    }

    public function addReclamationsAdmin(Reclamation $reclamationsAdmin): self
    {
        if (!$this->reclamationsAdmin->contains($reclamationsAdmin)) {
            $this->reclamationsAdmin[] = $reclamationsAdmin;
            $reclamationsAdmin->setAdministrateur($this);
        }

        return $this;
    }

    public function removeReclamationsAdmin(Reclamation $reclamationsAdmin): self
    {
        if ($this->reclamationsAdmin->contains($reclamationsAdmin)) {
            $this->reclamationsAdmin->removeElement($reclamationsAdmin);
            // set the owning side to null (unless already changed)
            if ($reclamationsAdmin->getAdministrateur() === $this) {
                $reclamationsAdmin->setAdministrateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Terrain[]
     */
    public function getTerrains(): Collection
    {
        return $this->terrains;
    }

    public function addTerrain(Terrain $terrain): self
    {
        if (!$this->terrains->contains($terrain)) {
            $this->terrains[] = $terrain;
            $terrain->setProprietaire($this);
        }

        return $this;
    }

    public function removeTerrain(Terrain $terrain): self
    {
        if ($this->terrains->contains($terrain)) {
            $this->terrains->removeElement($terrain);
            // set the owning side to null (unless already changed)
            if ($terrain->getProprietaire() === $this) {
                $terrain->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Boutique[]
     */
    public function getBoutiques(): Collection
    {
        return $this->boutiques;
    }

    public function addBoutique(Boutique $boutique): self
    {
        if (!$this->boutiques->contains($boutique)) {
            $this->boutiques[] = $boutique;
            $boutique->setPropritaire($this);
        }

        return $this;
    }

    public function removeBoutique(Boutique $boutique): self
    {
        if ($this->boutiques->contains($boutique)) {
            $this->boutiques->removeElement($boutique);
            // set the owning side to null (unless already changed)
            if ($boutique->getPropritaire() === $this) {
                $boutique->setPropritaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setClient($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getClient() === $this) {
                $produit->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommnadesLivreur(): Collection
    {
        return $this->commnadesLivreur;
    }

    public function addCommnadesLivreur(Commandes $commnadesLivreur): self
    {
        if (!$this->commnadesLivreur->contains($commnadesLivreur)) {
            $this->commnadesLivreur[] = $commnadesLivreur;
            $commnadesLivreur->setLivreur($this);
        }

        return $this;
    }

    public function removeCommnadesLivreur(Commandes $commnadesLivreur): self
    {
        if ($this->commnadesLivreur->contains($commnadesLivreur)) {
            $this->commnadesLivreur->removeElement($commnadesLivreur);
            // set the owning side to null (unless already changed)
            if ($commnadesLivreur->getLivreur() === $this) {
                $commnadesLivreur->setLivreur(null);
            }
        }

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
            $avi->setClient($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->contains($avi)) {
            $this->avis->removeElement($avi);
            // set the owning side to null (unless already changed)
            if ($avi->getClient() === $this) {
                $avi->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setPrestataire($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getPrestataire() === $this) {
                $service->setPrestataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Demande[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setClient($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            // set the owning side to null (unless already changed)
            if ($demande->getClient() === $this) {
                $demande->setClient(null);
            }
        }

        return $this;
    }

    public function getExtraInfo(): ?ExtraInfo
    {
        return $this->extraInfo;
    }

    public function setExtraInfo(?ExtraInfo $extraInfo): self
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }

    public function getInfoUser(): ?self
    {
        return $this->infoUser;
    }

    public function setInfoUser(?self $infoUser): self
    {
        $this->infoUser = $infoUser;

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUtilisateur($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
            // set the owning side to null (unless already changed)
            if ($chat->getUtilisateur() === $this) {
                $chat->setUtilisateur(null);
            }
        }

        return $this;
    }

}
