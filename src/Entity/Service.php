<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbre_questions;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255, nullable=false)
     */
    private $img;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieService::class, inversedBy="services")
     */
    private $CategorieService;

    /**
     * @ORM\OneToMany(targetEntity=Demande::class, mappedBy="service")
     */
    private $demandes;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNbreQuestions()
    {
        return $this -> nbre_questions;
    }

    /**
     * @param mixed $nbre_questions
     */
    public function setNbreQuestions($nbre_questions): void
    {
        $this -> nbre_questions = $nbre_questions;
    }

    /**
     * @return string
     */
    public function getImg(): ?string
    {
        return $this -> img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this -> img = $img;
    }

    public function getCategorieService(): ?CategorieService
    {
        return $this->CategorieService;
    }

    public function setCategorieService(?CategorieService $CategorieService): self
    {
        $this->CategorieService = $CategorieService;

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
            $demande->setService($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            // set the owning side to null (unless already changed)
            if ($demande->getService() === $this) {
                $demande->setService(null);
            }
        }

        return $this;
    }
}
