<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoutiqueRepository::class)
 */
class Boutique
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
    private $nomBoutique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\OneToOne(targetEntity=Emplacement::class, mappedBy="boutique", cascade={"persist", "remove"})
     */
    private $emplacement;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     */
    private $propritaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomBoutique(): ?string
    {
        return $this->nomBoutique;
    }

    public function setNomBoutique(string $nomBoutique): self
    {
        $this->nomBoutique = $nomBoutique;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setTerrain(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;

        // set (or unset) the owning side of the relation if necessary
        $newBoutique = null === $emplacement ? null : $this;
        if ($emplacement->getBoutique() !== $newBoutique) {
            $emplacement->setBoutique($newBoutique);
        }

        return $this;
    }

    public function getPropritaire(): ?Utilisateur
    {
        return $this->propritaire;
    }

    public function setPropritaire(?Utilisateur $propritaire): self
    {
        $this->propritaire = $propritaire;

        return $this;
    }
}
