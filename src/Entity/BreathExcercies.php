<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BreathExcerciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: BreathExcerciesRepository::class,)]
#[ApiResource(
    operations: [
        new Post(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Get(),
        new GetCollection(),
        new GetCollection(
            uriTemplate: '/GetRecommendations',
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        )
    ]
)]
class BreathExcercies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[SerializedName("Title")]
    private ?string $title = null;

    #[ORM\Column]
    #[SerializedName("TimerBreathIn")]
    private ?int $timer_breath_in = null;

    #[ORM\Column]
    #[SerializedName("TimerBreathOut")]
    private ?int $timer_breath_out = null;

    #[ORM\Column]
    #[SerializedName("TimerApnea")]
    private ?int $timer_apnea = null;

    #[ORM\Column]
    #[SerializedName("isActive")]
    private ?bool $is_active = null;

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

    #[Ignore]
    public function getTimerBreathIn(): ?int
    {
        return $this->timer_breath_in;
    }

    public function setTimerBreathIn(int $timer_breath_in): static
    {
        $this->timer_breath_in = $timer_breath_in;

        return $this;
    }

    #[Ignore]
    public function getTimerBreathOut(): ?int
    {
        return $this->timer_breath_out;
    }

    public function setTimerBreathOut(int $timer_breath_out): static
    {
        $this->timer_breath_out = $timer_breath_out;

        return $this;
    }

    #[Ignore]
    public function getTimerApnea(): ?int
    {
        return $this->timer_apnea;
    }

    public function setTimerApnea(int $timer_apnea): static
    {
        $this->timer_apnea = $timer_apnea;

        return $this;
    }

    #[Ignore]
    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }
}
