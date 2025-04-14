<?php

// src/Entity/Criterion.php
namespace App\Entity;

use App\Repository\CriterionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CriterionRepository::class)]
class Criterion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["filter_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)] // Adjust length as needed
    #[Groups(["filter_read"])]
    private ?string $type = null; // e.g., "field", "header", etc.

    #[ORM\Column(length: 255)] // Adjust length as needed
    #[Groups(["filter_read"])]
    private ?string $comparator = null; // e.g., "=", "!=", ">", "<", etc.

    #[ORM\Column(length: 255)] // Adjust length as needed, or use a different type if needed (e.g., TextType for longer values)
    #[Groups(["filter_read"])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'criteria')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Filter $filter = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFilter(): ?Filter
    {
        return $this->filter;
    }

    public function setFilter(?Filter $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}

// ... (Filter.php, FilterController.php, and Repositories remain the same - see previous response)