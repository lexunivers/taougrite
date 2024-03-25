<?php

namespace App\Entity;

use App\Repository\AvionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AvionsRepository::class)]
class Avions
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
    private $immatriculation;

    #[ORM\Column(type: 'string', length: 255)]
    private $marque;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $puissance;

    #[ORM\Column(type: 'string', length: 255)]
    private $anneemodele;

    #[ORM\Column(type: 'string', length: 255)]
    private $anneeachat;

    #[ORM\Column(type: 'string', length: 255)]
    private $anneerevente;

    #[ORM\Column(type: 'string', length: 255)]
    private $essence;

    #[ORM\Column(type: 'string', length: 255)]
    private $place;

    #[ORM\Column(type: 'integer')]
    private $valeur;

    #[ORM\Column(type: 'string', length: 255)]
    private $heuresMoteur;

    #[ORM\Column(type: 'string', length: 255)]
    private $heuresCellule;

    #[ORM\Column(type: 'boolean')]
    private $enparc;

    #[ORM\Column(type: 'date')]
    private $datefiche;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $eventColor;

    #[ORM\Column(type: 'integer')]
    private $tarifHoraire;

    #[ORM\Column(type: 'integer')]
    private $instruction;

    #[ORM\OneToOne(targetEntity: Resources::class, cascade: ['persist', 'remove', 'refresh'])]
    private $ResourcesId;

   // #[ORM\OneToMany(mappedBy: 'avion', targetEntity: Vol::class)]
   // private $vols;

   // public function __construct()
   // {
   //     $this->vols = new ArrayCollection();
   // }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPuissance(): ?string
    {
        return $this->puissance;
    }

    public function setPuissance(string $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getAnneemodele(): ?string
    {
        return $this->anneemodele;
    }

    public function setAnneemodele(string $anneemodele): self
    {
        $this->anneemodele = $anneemodele;

        return $this;
    }

    public function getAnneeachat(): ?string
    {
        return $this->anneeachat;
    }

    public function setAnneeachat(string $anneeachat): self
    {
        $this->anneeachat = $anneeachat;

        return $this;
    }

    public function getAnneerevente(): ?string
    {
        return $this->anneerevente;
    }

    public function setAnneerevente(string $anneerevente): self
    {
        $this->anneerevente = $anneerevente;

        return $this;
    }

    public function getEssence(): ?string
    {
        return $this->essence;
    }

    public function setEssence(string $essence): self
    {
        $this->essence = $essence;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getHeuresMoteur(): ?string
    {
        return $this->heuresMoteur;
    }

    public function setHeuresMoteur(string $heuresMoteur): self
    {
        $this->heuresMoteur = $heuresMoteur;

        return $this;
    }

    public function getHeuresCellule(): ?string
    {
        return $this->heuresCellule;
    }

    public function setHeuresCellule(string $heuresCellule): self
    {
        $this->heuresCellule = $heuresCellule;

        return $this;
    }

    public function isEnparc(): ?bool
    {
        return $this->enparc;
    }

    public function setEnparc(bool $enparc): self
    {
        $this->enparc = $enparc;

        return $this;
    }

    public function getDatefiche(): ?\DateTimeInterface
    {
        return $this->datefiche;
    }

    public function setDatefiche(\DateTimeInterface $datefiche): self
    {
        $this->datefiche = $datefiche;

        return $this;
    }

    public function getEventColor(): ?string
    {
        return $this->eventColor;
    }

    public function setEventColor(?string $eventColor): self
    {
        $this->eventColor = $eventColor;

        return $this;
    }

    public function getTarifHoraire(): ?int
    {
        return $this->tarifHoraire;
    }

    public function setTarifHoraire(int $tarifHoraire): self
    {
        $this->tarifHoraire = $tarifHoraire;

        return $this;
    }

    public function getInstruction(): ?int
    {
        return $this->instruction;
    }

    public function setInstruction(int $instruction): self
    {
        $this->instruction = $instruction;

        return $this;
    }

    //----- Cette function est utilisée pour le champ "Avion" dans ConfigureFormFields de VolAdmin
    public function __toString()
    {
        return $this->type . "  " . $this->immatriculation;
    }
   
    //---- Cette function est utilisée dans AvionAdmin ConfigureFormList
    public function getAffichageAvions()
    {
        return $this->marque.' - '.$this->type.' - '.$this->immatriculation;
    }
    
    // ---- Données pour configureListFields(FormMapper VolAdmin) et public function "TarifApplicable" dans Vol.php --
    
    public function getTarifSolo()
    {
        return $this->tarifHoraire;
    }

    public function getTarifInstruction()
    {
        return $this->instruction;
    }

    public function getTarifEcole()
    {
        $tarifEcole = ($this->tarifHoraire + $this->instruction);
        
        return $tarifEcole;
    }

    public function getResourcesId(): ?Resources
    {
        return $this->ResourcesId;
    }

    public function setResourcesId(?Resources $ResourcesId): self
    {
        $this->ResourcesId = $ResourcesId;

        return $this;
    }


}


