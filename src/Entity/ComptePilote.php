<?php

namespace App\Entity;

use App\Repository\ComptePiloteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComptePiloteRepository::class)]
class ComptePilote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy:'comptepilote', cascade: ['persist', 'remove'])]
    private $pilote;

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

    public function getPilote(): ?User
    {
        return $this->pilote;
    }

    public function setPilote(?User $pilote): self
    {
        $this->pilote = $pilote;

        return $this;
    }

}
