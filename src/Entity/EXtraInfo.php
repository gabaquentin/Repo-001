<?php

namespace App\Entity;

use App\Repository\EXtraInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EXtraInfoRepository::class)
 */
class EXtraInfo
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
    private $whishlist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWhishlist(): ?string
    {
        return $this->whishlist;
    }

    public function setWhishlist(string $whishlist): self
    {
        $this->whishlist = $whishlist;

        return $this;
    }
}
