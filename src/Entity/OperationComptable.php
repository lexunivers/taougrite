<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\OperationComptableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationComptableRepository::class)]
class OperationComptable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;
    
    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private $OperMontant;

    #[ORM\Column(type: 'integer')]
    private $OperSensMt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $Libelle;

    #[ORM\Column(type: 'datetime')]
    private $OperDate;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $CompteId;

    
    public function getOperDate(): ?\DateTimeInterface
    {
        return $this->OperDate;
    }

    public function setOperDate(\DateTimeInterface $OperDate): self
    {
        $this->OperDate = $OperDate;

        return $this;
    }
    
    public function __construct()
    {
        $this->OperDate = new \DateTime('now');
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


    public function getOperMontant(): ?string
    {
        return $this->OperMontant;
    }

    public function setOperMontant(string $OperMontant): self
    {
        $this->OperMontant = $OperMontant;

        return $this;
    }

    public function getOperSensMt(): ?int
    {
        return $this->OperSensMt;
    }

    public function setOperSensMt(int $OperSensMt): self
    {
        $this->OperSensMt = $OperSensMt;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(?string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getLibelleCotis()
    {
        return 	'Cotisation Club ';
    }
    
    public function getLibelleFFA()
    {
        return 	'Licence FFA ';
    }    

    public function getLibelleInfo()
    {
        return 	'Abonnement InfoPilote ';
    }    

    public function somme()
    {
        $somme += $this->getOperMontant();
    
        return $somme;
    }

    public function getCompteId(): ?int
    {
        return $this->CompteId;
    }

    public function setCompteId(?int $CompteId): self
    {
        $this->CompteId = $CompteId;

        return $this;
    }
}
    
