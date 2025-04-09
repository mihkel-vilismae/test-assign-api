<?php

namespace App\Entity;

use App\Repository\FilterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FilterRepository::class)]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['filter_with_criteria'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['filter_with_criteria'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['filter_with_criteria'])]
    private ?string $selection = null;

    #[ORM\OneToMany(mappedBy: 'filter', targetEntity: Criteria::class, cascade: ["persist", "remove"])]
    #[Groups(['filter_with_criteria'])] // Add this line if it's missing
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

    public function getSelection(): ?string
    {
        return $this->selection;
    }

    public function setSelection(string $selection): self
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * @return Collection<int, Criteria>
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriteria(Criteria $criteria): self
    {
        if (!$this->criteria->contains($criteria)) {
            $this->criteria->add($criteria);
            $criteria->setFilter($this);
        }

        return $this;
    }

    public function removeCriteria(Criteria $criteria): self
    {
        if ($this->criteria->removeElement($criteria)) {
            // set the owning side to null (unless already changed)
            if ($criteria->getFilter() === $this) {
                $criteria->setFilter(null);
            }
        }

        return $this;
    }
}