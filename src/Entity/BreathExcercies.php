<?php

namespace App\Entity;

use App\Repository\BreathExcerciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BreathExcerciesRepository::class)]
class BreathExcercies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $timer_breath_in = null;

    #[ORM\Column]
    private ?int $timer_breath_out = null;

    #[ORM\Column]
    private ?int $timer_apnea = null;

    #[ORM\Column]
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

    public function getTimerBreathIn(): ?int
    {
        return $this->timer_breath_in;
    }

    public function setTimerBreathIn(int $timer_breath_in): static
    {
        $this->timer_breath_in = $timer_breath_in;

        return $this;
    }

    public function getTimerBreathOut(): ?int
    {
        return $this->timer_breath_out;
    }

    public function setTimerBreathOut(int $timer_breath_out): static
    {
        $this->timer_breath_out = $timer_breath_out;

        return $this;
    }

    public function getTimerApnea(): ?int
    {
        return $this->timer_apnea;
    }

    public function setTimerApnea(int $timer_apnea): static
    {
        $this->timer_apnea = $timer_apnea;

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
}
