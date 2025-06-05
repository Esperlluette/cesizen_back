<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ApiResource]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private array $content = [];

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $creation_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $last_update_date = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\ManyToOne(inversedBy: 'pages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppUser $created_by = null;

    #[ORM\OneToOne(inversedBy: 'pages_updated', cascade: ['persist', 'remove'])]
    private ?AppUser $updated_by = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTime $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTime
    {
        return $this->last_update_date;
    }

    public function setLastUpdateDate(\DateTime $last_update_date): static
    {
        $this->last_update_date = $last_update_date;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getCreatedBy(): ?AppUser
    {
        return $this->created_by;
    }

    public function setCreatedBy(?AppUser $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getUpdatedBy(): ?AppUser
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?AppUser $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }
}
