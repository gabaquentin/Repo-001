<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="integer")
     */
    private $blog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reponse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getUtilisateur(): ?int
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(int $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getBlog(): ?int
    {
        return $this->blog;
    }

    public function setBlog(int $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getReponse(): ?int
    {
        return $this->reponse;
    }

    public function setReponse(int $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDate(): ?string
    {
        $stringValue = $this->date->format('Y-m-d H:i:s');
        return $stringValue;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
