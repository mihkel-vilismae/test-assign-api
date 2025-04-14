<?php

namespace App\Entity;

use App\Repository\FilterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FilterRepository::class)]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["filter_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["filter_read"])]
    private ?string $name = null;

    #[ORM\Column(type: "text", nullable: true)] // Using text type for potentially longer selections
    #[Groups(["filter_read"])]
    private ?string $selection = null; // Now a string


    /**
     * @var Collection<int, Criterion>
     */
    #[ORM\OneToMany(mappedBy: 'filter', targetEntity: Criterion::class, cascade:["persist"], orphanRemoval: true)]
    #[Groups(["filter_read"])]
    private Collection $criteria;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSelection(): ?string  // Return type is now string
    {
        return $this->selection;
    }

    public function setSelection(?string $selection): self // Parameter type is now string
    {
        $this->selection = $selection;

        return $this;
    }


    /**
     * @return Collection<int, Criterion>
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriterion(Criterion $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria->add($criterion);
            $criterion->setFilter($this);
        }

        return $this;
    }

    public function removeCriterion(Criterion $criterion): self
    {
        if ($this->criteria->removeElement($criterion)) {
            if ($criterion->getFilter() === $this) {
                $criterion->setFilter(null);
            }
        }

        return $this;
    }
}