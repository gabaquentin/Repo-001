<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PackRepository::class)
 */
class Pack
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
    private $boutique;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="pack", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoutique(): ?int
    {
        return $this->boutique;
    }

    public function setBoutique(?int $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newPack = null === $user ? null : $this;
        if ($user->getPack() !== $newPack) {
            $user->setPack($newPack);
        }

        return $this;
    }
}
