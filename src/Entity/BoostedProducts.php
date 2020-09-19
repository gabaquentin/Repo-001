<?php

namespace App\Entity;

use App\Repository\BoostedProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoostedProductsRepository::class)
 */
class BoostedProducts
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
    private $BoostedProducts = "a:0:{}";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoostedProducts(): ?array
    {
        return unserialize($this->BoostedProducts);
    }

    public function setBoostedProducts(array $BoostedProducts): self
    {
        $this->BoostedProducts = serialize($BoostedProducts);

        return $this;
    }
}
