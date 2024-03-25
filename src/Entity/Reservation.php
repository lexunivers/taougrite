<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'datetime')]
    private $start;

    #[ORM\Column(type: 'datetime')]
    private $end;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $resourceId;

    #[ORM\ManyToOne(targetEntity: Instructeur::class,)]// inversedBy: 'reservations')]
    private $instructeur;

    #[ORM\ManyToOne(targetEntity: User::class,)]// inversedBy: 'reservations')]
    private $user;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $reservataire;

    #[ORM\Column(type: 'string', length: 255)]
    private $appareil;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $formateur;

    #[ORM\ManyToOne(targetEntity: Avions::class)]
    private $avion;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $realisation;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $numeroOrdre;

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    public function setResourceId(string $resourceId): self
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    public function getInstructeur(): ?Instructeur
    {
        return $this->instructeur;
    }

    public function setInstructeur(?Instructeur $instructeur): self
    {
        $this->instructeur = $instructeur;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReservataire(): ?int
    {
        return $this->reservataire;
    }

    public function setReservataire(?int $reservataire): self
    {
        $this->reservataire = $reservataire;

        return $this;
    }

    public function getAppareil(): ?string
    {
        return $this->appareil;
    }

    public function setAppareil(string $appareil): self
    {
        $this->appareil = $appareil;

        return $this;
    }

    public function getFormateur(): ?string
    {
        return $this->formateur;
    }

    public function setFormateur(?string $formateur): self
    {
        $this->formateur = $formateur;

        return $this;
    }

    public function getAvion(): ?Avions
    {
        return $this->avion;
    }

    public function setAvion(?Avions $avion): self
    {
        $this->avion = $avion;

        return $this;
    }

    public function isRealisation(): ?bool
    {
        return $this->realisation;
    }

    public function setRealisation(?bool $realisation): self
    {
        $this->realisation = $realisation;

        return $this;
    }

    public function getNumeroOrdre(): ?string
    {
        return $this->numeroOrdre;
    }

    public function setNumeroOrdre(?string $numeroOrdre): self
    {
        $this->numeroOrdre = $numeroOrdre;

        return $this;
    }

    public function toString($object): string
    {

        return $this->NumeroOrdre ;        
        return $object instanceof Reservation
            ? $object->getTitle()
            : 'Reservation'; // shown in the breadcrumb on the create view
    }

    public function __toString(): string
    {
          return $this->numeroOrdre ;
	}
}


