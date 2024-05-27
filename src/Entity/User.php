<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

use Sonata\UserBundle\Entity\BaseUser;
use Sonata\UserBundle\Model\UserInterface;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserRepository::class)]

class User extends BaseUser
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $residence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hobby = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codepostal = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $timezone;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $locale;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $resetToken;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToOne(targetEntity: ComptePilote::class, mappedBy: 'pilote', cascade: ['persist', 'remove'])]
    private $comptepilote;
 
    #[ORM\Column(type: 'datetime', options:['default'=>'CURRENT_TIMESTAMP'] )]
    private $updatedAt2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
     * @var File
     * @Assert\File(mimeTypes={ "image/png", "image/jpeg", "image/jpg" })
     * @Vich\UploadableField(mapping="uploads", fileNameProperty="imageName")
     */

    #[Vich\UploadableField(mapping: 'uploads', fileNameProperty: 'imageName',)]
    private ?File $imageFile = null;

    #[ORM\ManyToMany(targetEntity: Qualification::class, mappedBy: 'qualifsLegales', cascade: ['persist', 'remove'] )]
    private Collection $qualifications;    
    

    /**
     * Set imageFile
     *
     * @param File|UploadedFile $imageFile
     *
     * @return Post
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt2 = new \DateTime('now');
        }
        return $this;
    }

    /**
     * Get imageFile
     *
     * @return File|UploadedFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }    

    public function getUpdatedAt2(): ?\DateTimeInterface
    {
        return $this->updatedAt2;
    }

    public function setUpdatedAt2(\DateTimeInterface $updatedAt2): self
    {
        $this->updatedAt2 = $updatedAt2;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }    

	public function getComptepilote(): ?Comptepilote
                   {
                       return $this->comptepilote;
                   }

    public function setComptepilote(?Comptepilote $comptepilote): self
    {
        // unset the owning side of the relation if necessary
        if ($comptepilote === null && $this->comptepilote !== null) {
            $this->comptepilote->setPilote(null);
        }

        // set the owning side of the relation if necessary
        if ($comptepilote !== null && $comptepilote->getPilote() !== $this) {
            $comptepilote->setPilote($this);
        }

        $this->comptepilote = $comptepilote;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResidence(): ?string
    {
        return $this->residence;
    }

    public function setResidence(?string $residence): static
    {
        $this->residence = $residence;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getHobby(): ?string
    {
        return $this->hobby;
    }

    public function setHobby(?string $hobby): static
    {
        $this->hobby = $hobby;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }


    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(?string $codepostal): static
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function __construct()
    {       
        $this->updatedAt2 = new \DateTime('now');
        $this->qualifications = new ArrayCollection();
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, # Please, use DTO instead of entities
        ]);
    }

    /**
     * @return Collection<int, Qualification>
     */
    public function getQualifications(): Collection
    {
        return $this->qualifications;
    }

    public function addQualification(Qualification $qualification): static
    {
        if (!$this->qualifications->contains($qualification)) {
            $this->qualifications->add($qualification);
            $qualification->addQualifsLegale($this);
        }

        return $this;
    }

    public function removeQualification(Qualification $qualification): static
    {
        if ($this->qualifications->removeElement($qualification)) {
            $qualification->removeQualifsLegale($this);
        }

        return $this;
    }

 
}