<?php

namespace App\Entity;

use App\Repository\InstructeurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: InstructeurRepository::class)]
class Instructeur
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
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $initiales;

   // #[ORM\OneToMany(mappedBy: 'instructeur', targetEntity: Vol::class)]
   // private $vols;

   // #[ORM\OneToMany(mappedBy: 'instructeur', targetEntity: Reservation::class)]
   // private $reservations;

   // public function __construct()
   // {
   //     $this->facture = new ArrayCollection();
   //     $this->vols = new ArrayCollection();
   //     $this->reservations = new ArrayCollection();
    //}

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();//.' '.$this->getLastName();
    }

    public function getInitiales(): ?string
    {
        return $this->initiales;
    }

    public function setInitiales(string $initiales): self
    {
        $this->initiales = $initiales;

        return $this;
    }


//
//    /**
//     * @return Collection<int, Vol>
//     */
//    public function getVols(): Collection
//    {
//        return $this->vols;
//    }

//    public function addVol(Vol $vol): self
//    {
//        if (!$this->vols->contains($vol)) {
//            $this->vols[] = $vol;
//            $vol->setInstructeur($this);
//        }
//
//        return $this;
//    }

//    public function removeVol(Vol $vol): self
//    {
//        if ($this->vols->removeElement($vol)) {
//            // set the owning side to null (unless already changed)
//            if ($vol->getInstructeur() === $this) {
//                $vol->setInstructeur(null);
//            }
//        }
//
//        return $this;
//    }

//    /**
//     * @return Collection<int, Reservation>
//     */
//    public function getReservations(): Collection
//    {
//        return $this->reservations;
//    }

//    public function addReservation(Reservation $reservation): self
//    {
//        if (!$this->reservations->contains($reservation)) {
//            $this->reservations[] = $reservation;
//            $reservation->setInstructeur($this);
//        }
//
//        return $this;
//    }

//    public function removeReservation(Reservation $reservation): self
//    {
//        if ($this->reservations->removeElement($reservation)) {
//            // set the owning side to null (unless already changed)
//            if ($reservation->getInstructeur() === $this) {
//                $reservation->setInstructeur(null);
//            }
//        }
//
//        return $this;
//    }    
}
