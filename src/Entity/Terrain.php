<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string', length: 255)]
    private $indicatif;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'integer')]
    private $dept;

    public function getIndicatif(): ?string
    {
        return $this->indicatif;
    }

    public function setIndicatif(string $indicatif): self
    {
        $this->indicatif = $indicatif;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDept(): ?int
    {
        return $this->dept;
    }

    public function setDept(int $dept): self
    {
        $this->dept = $dept;

        return $this;
    }

    public function __toString()
    {
        return $this->indicatif.' - '.$this->ville.' - '.$this->dept;
    }
    
    public function getTerrain()
    {
        return $this->indicatif.' - '.$this->ville.' - '.$this->dept;
    }
}


