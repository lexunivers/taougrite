<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'date')]
    private $datevol;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'vols')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Avions::class, inversedBy: 'vols')]
    private $avion;

    #[ORM\ManyToOne(targetEntity: Typevol::class, inversedBy: 'vols')]
    #[ORM\JoinColumn(nullable: false)]
    private $typevol;

    #[ORM\ManyToOne(targetEntity: Naturevol::class, inversedBy: 'vols')]
    #[ORM\JoinColumn(nullable: false)]
    private $naturevol;

    #[ORM\ManyToOne(targetEntity: Terrain::class)]
    private $lieuDepart;

    #[ORM\ManyToOne(targetEntity: Terrain::class)]
    private $lieuArrivee;

    #[ORM\Column(type: 'time')]
    private $heureDepart;

    #[ORM\Column(type: 'time')]
    private $heureArrivee;

    #[ORM\ManyToOne(targetEntity: Instructeur::class, inversedBy: 'vols')]
    private $instructeur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $CodeReservation;

    #[ORM\Column(type: 'date')]
    private $operDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $facture;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $validation;

    #[ORM\ManyToOne(targetEntity: OperationComptable::class, inversedBy: 'CompteId', cascade: ['persist', 'remove', 'refresh'])]
    private $Comptable;
    
    public function getComptable(): ?OperationComptable
    {
        return $this->Comptable;
    }

    public function setComptable(?OperationComptable $Comptable): self
    {
        $this->Comptable = $Comptable;

        return $this;
    }    
    
    public function getDatevol(): ?\DateTimeInterface
    {
        return $this->datevol;
    }

    public function setDatevol(\DateTimeInterface $datevol): self
    {
        $this->datevol = $datevol;

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

    public function getAvion(): ?Avions
    {
        return $this->avion;
    }

    public function setAvion(?Avions $avion): self
    {
        $this->avion = $avion;

        return $this;
    }

    public function getTypevol(): ?Typevol
    {
        return $this->typevol;
    }

    public function setTypevol(?Typevol $typevol): self
    {
        $this->typevol = $typevol;

        return $this;
    }

    public function getNaturevol(): ?Naturevol
    {
        return $this->naturevol;
    }

    public function setNaturevol(?Naturevol $naturevol): self
    {
        $this->naturevol = $naturevol;

        return $this;
    }

    public function getLieuDepart(): ?Terrain
    {
        return $this->lieuDepart;
    }

    public function setLieuDepart(?Terrain $lieuDepart): self
    {
        $this->lieuDepart = $lieuDepart;

        return $this;
    }

    public function getLieuArrivee(): ?Terrain
    {
        return $this->lieuArrivee;
    }

    public function setLieuArrivee(?Terrain $lieuArrivee): self
    {
        $this->lieuArrivee = $lieuArrivee;

        return $this;
    }

    public function getHeureDepart(): ?\DateTimeInterface
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(\DateTimeInterface $heureDepart): self
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    public function getHeureArrivee(): ?\DateTimeInterface
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(\DateTimeInterface $heureArrivee): self
    {
        $this->heureArrivee = $heureArrivee;

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

    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(?string $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(?bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }
     
    public function getOperDate(): ?\DateTimeInterface
    {
        return $this->operDate;
    }

    public function setOperDate(\DateTimeInterface $operDate): self
    {
        $this->operDate = $operDate;

        return $this;
    }

    public function __construct()
    {
        $this->operDate = new \DateTime('now');
       
    }


    public function getCodeReservation(): ?string
    {
        return $this->CodeReservation;
    }

    public function setCodeReservation(string $CodeReservation): self
    {
        $this->CodeReservation = $CodeReservation;

        return $this;
    }    

    
    //Récuperation de $reservataire pour la requête dans formBuilder de VolType
     
    public function Reservataire(){
        $reservataire = $this->getUser('session')->getId();

    }
    
    // ----- Affichage dans ConfigList selon qu'il s'agit d'un vol Solo, Ecole

    public function AffichageEcole()
    {
        if ($this->typevol == "Solo") {
            $affichageEcole = " n/a ";
        } elseif ($this->typevol =="Ecole") {
            $affichageEcole = $this->avion->getTarifInstruction();
        }
            
        return $affichageEcole;
    }
    
    public function AffichageInstructeur()
    {
        if ($this->typevol == "Solo") {
            $affichageInstructeur = " n/a ";
        } else {
            $affichageInstructeur = $this->instructeur;
        }
        return $affichageInstructeur;
    }


    // ----- function pour cumul des heures de fonctionnement du moteur
	
	public function add_heures($heure1,$heure2){
                                             			
                    $vol = new vol();
                    $secondes1= $vol->heure_to_secondes($heure1);
                    $secondes2= $vol->heure_to_secondes($heure2);
                    $somme=$secondes1+$secondes2;
                    //transfo en h:i:s
                    $s=$somme % 60; //reste de la division en minutes => secondes
                    $m1=($somme-$s) / 60; //minutes totales
                    $m=$m1 % 60;//reste de la division en heures => minutes
                    $h=($m1-$m) / 60; //heures
                    $resultat=$h.":".$m.":".$s;
                    return $resultat;
                }

    // ----- function lorsqu'un vol est supprimer par suite erreur de saisie, on ajuste tmps fonctionnement du moteur
	public function diff_heures($heure1,$heure2){
                                             			
                    $vol = new vol();
                    $secondes1= $vol->heure_to_secondes($heure1);
                    $secondes2= $vol->heure_to_secondes($heure2);
                    $diff=$secondes1-$secondes2;
                    //transfo en h:i:s
                    $s=$diff % 60; //reste de la division en minutes => secondes
                    $m1=($diff-$s) / 60; //minutes totales
                    $m=$m1 % 60;//reste de la division en heures => minutes
                    $h=($m1-$m) / 60; //heures
                    $resultat=$h.":".$m.":".$s;
                    return $resultat;
                }

	public function heure_to_secondes($heure){
                    $array_heure=explode(":",$heure);
                    $secondes=3600*$array_heure[0]+60*$array_heure[1]+$array_heure[2];
                    return $secondes;
                }


    public function HeuresdeF()
    {
        $HeuresdeF = $this->avion->getHeuresMoteur();
        return $HeuresdeF;
    }

    public function HeuresdeFcellule()
    {
        $HeuresdeFcellule = $this->avion->getHeuresCellule();
        return $HeuresdeFcellule;
    }    
    

    // A - Elements à récupérer pour le calcul du montant à facturer au Pilote
    //------------------------------------------------------------------------

    // -1/- Détermination de la durée du Vol //
       
    public function DureeDuVol()
    {
        $dureeduvol = date_diff($this->heureDepart, $this->heureArrivee);
        return $dureeduvol->format('%H:%I:%S');
    }

    public function totalHvol(){
        $duree = $this->DureeDuVol('H:I');
        $tempsdeVol = strtotime($duree);
        //$totalHvol = new \DateTime('now');       
        //$sommedureedesvols = date_diff($this->heureDepart, $this->heureArrivee)  ;
        $totalHvol = $tempsdeVol; 
        return $totalHvol;
    }


      
    // -2/- Tarif Applicable selon le type de vol "Solo" ou "Ecole" //
    
    public function getTarifSolo()
    {
        $tarifsolo =  (int) (strval($this->avion->getTarifHoraire()));
            
        return $tarifsolo;
    }
    
    public function getTarifApplicable()
    {
        if ($this->typevol == "Solo") {
            $tarifapplicable = (int) (strval($this->avion->getTarifHoraire()));
        } else {
            $tarifapplicable =  (int) (strval($this->avion->getTarifHoraire() + $this->avion->getTarifInstruction()));
        }
            
        return $tarifapplicable;
    }
    

        
    // B -  Montant à facturer au Pilote
    //----------------------------------
    
    public function getMontantFacture()
    {        
        //on récupére le Tarif Applicable au type de vol
        $tarif = $this->getTarifApplicable();
            
        // calcul du Coût du vol à facturer
        
			// 1 - On récupere le temps de vol
			$duree = $this->DureeDuVol('H:I');
			$tempsdeVol = strtotime($duree);
					
			// 2 - Extraction des heures et minutes
			$heure = idate('H', $tempsdeVol);
			$minutes = idate('i', $tempsdeVol);
					
			// 3 - convertion en minutes
			$HeureMinutes = ($heure*60);
			$TempsTotal = $HeureMinutes + $minutes ;

			// 4 - calcul du montant
			$montantfacture = (($tarif/60)* $TempsTotal);
			$montantfacture = round($montantfacture, 2);
        
        // Montant à porter au débit du Compte Pilote
     
        return $montantfacture;
    }

    // c - Affichage du "Libelle" dans OpérationComptable/ Montant
    //----------------------------------------------------------

    public function getLibelle()
    {
        return 	'Date : '.$this->datevol->format('d-m-Y ').' - '.'Vol de : '.$this->DureeDuVol('%h:%I').' h/min -'.$this->avion.' - '.'Instructeur : '.$this->instructeur;
    }
   
    public function toString($object): string
    {
        return $object instanceof Vol
            ? $object->getTitle()
            : 'Vol'; // shown in the breadcrumb on the create view
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class, # Please, use DTO instead of entities
        ]);
    }

}
