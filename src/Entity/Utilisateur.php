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
     * @var InfoUser
     * @ORM\OneToOne(targetEntity=Utilisateur::class, cascade={"persist", "remove"})
     */
    private $infoUser;


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
     * @return InfoUser
     */
    public function getInfoUser(): ?InfoUser
    {
        return $this->infoUser;
    }

    /**
     * @param InfoUser $infoUser
     * @return Utilisateur
     */
    public function setInfoUser(InfoUser $infoUser): self
    {
        $this->infoUser = $infoUser;
        return $this;
    }


}
