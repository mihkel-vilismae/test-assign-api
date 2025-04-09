<?php

namespace App\Entity;

use App\Repository\CriteriaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CriteriaRepository::class)]
class Criteria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['filter_with_criteria'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'criteria')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filter $filter = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['filter_with_criteria'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['filter_with_criteria'])]
    private ?string $comparator = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['filter_with_criteria'])]
    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilter(): ?Filter
    {
        return $this->filter;
    }

    public function setFilter(?Filter $filter): self
    {
        $this->filter = $filter;

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

    public function getComparator(): ?string
    {
        return $this->comparator;
    }

    public function setComparator(string $comparator): self
    {
        $this->comparator = $comparator;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}