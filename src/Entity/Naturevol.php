<?php

namespace App\Entity;

use App\Repository\NaturevolRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: NaturevolRepository::class)]
class Naturevol
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
    private $naturevol;

    #[ORM\OneToMany(mappedBy: 'naturevol', targetEntity: Vol::class)]
    private $vols;

    public function __construct()
    {
        $this->vols = new ArrayCollection();
    }

    public function getNaturevol(): ?string
    {
        return $this->naturevol;
    }

    public function setNaturevol(string $naturevol): self
    {
        $this->naturevol = $naturevol;

        return $this;
    }

    public function __toString()
    {
        return $this->naturevol;
    }

    /**
     * @return Collection<int, Vol>
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): self
    {
        if (!$this->vols->contains($vol)) {
            $this->vols[] = $vol;
            $vol->setNaturevol($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): self
    {
        if ($this->vols->removeElement($vol)) {
            // set the owning side to null (unless already changed)
            if ($vol->getNaturevol() === $this) {
                $vol->setNaturevol(null);
            }
        }

        return $this;
    }
}
