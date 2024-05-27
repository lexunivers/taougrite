<?php

namespace App\Entity;

use App\Repository\QualificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: QualificationRepository::class)]
class Qualification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'qualifications', cascade: ['persist', 'remove'] )]
    private Collection $qualifsLegales;

    public function __construct()
    {
        $this->qualifsLegales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getQualifsLegales(): Collection
    {
        return $this->qualifsLegales;
    }

    public function addQualifsLegale(User $qualifsLegale): static
    {
        if (!$this->qualifsLegales->contains($qualifsLegale)) {
            $this->qualifsLegales->add($qualifsLegale);
        }

        return $this;
    }

    public function removeQualifsLegale(User $qualifsLegale): static
    {
        $this->qualifsLegales->removeElement($qualifsLegale);

        return $this;
    }
}
