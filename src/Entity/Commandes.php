<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandesRepository", repositoryClass=CommandesRepository::class)
 */
class Commandes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param mixed $numero
     * @return Commandes
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $numero;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modePaiement;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $panier="a:0:{}";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Infolivraison="a:0:{}";

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modeLivraison;

    /**
     * @ORM\Column(type="integer")
     */
    private $prixLivraison;

    /**
     * @ORM\Column(type="integer")
     */
    private $remise;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $livreur;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getModeLivraison()
    {
        return $this->modeLivraison;
    }

    /**
     * @param mixed $modeLivraison
     * @return Commandes
     */
    public function setModeLivraison($modeLivraison)
    {
        $this->modeLivraison = $modeLivraison;
        return $this;
    }


    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->dateCom;
    }

    public function setDateCom(\DateTimeInterface $dateCom): self
    {
        $this->dateCom = $dateCom;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): self
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * @param mixed $remise
     * @return Commandes
     */
    public function setRemise($remise) : self
    {
        $this->remise = $remise;
        return $this;
    }


    public function getPanier(): ?array
    {
        return unserialize($this->panier);
    }

    public function setPanier(array $panier): self
    {
        $this->panier = serialize($panier);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrixLivraison()
    {
        return $this->prixLivraison;
    }

    /**
     * @param mixed $prixLivraison
     * @return Commandes
     */
    public function setPrixLivraison($prixLivraison)
    {
        $this->prixLivraison = $prixLivraison;
        return $this;
    }


    public function getInfoLivraison(): ?array
    {
        return unserialize($this->Infolivraison);
    }

    public function setInfoLivraison(array $info): self
    {
        $this->Infolivraison = serialize($info);

        return $this;
    }
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }
}
