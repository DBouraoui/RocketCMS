<?php

namespace App\Entity;

use App\Repository\OpeningHoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHoursRepository::class)]
class OpeningHours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $day = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $openMorning = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $closeMorning = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $openAfternoon = null;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $closeAfternoon = null;

    #[ORM\Column]
    private ?bool $isclosed = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(?string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getOpenMorning(): ?string
    {
        return $this->openMorning;
    }

    public function setOpenMorning(?string $openMorning): static
    {
        $this->openMorning = $openMorning;

        return $this;
    }

    public function getCloseMorning(): ?string
    {
        return $this->closeMorning;
    }

    public function setCloseMorning(?string $closeMorning): static
    {
        $this->closeMorning = $closeMorning;

        return $this;
    }

    public function getOpenAfternoon(): ?string
    {
        return $this->openAfternoon;
    }

    public function setOpenAfternoon(?string $openAfternoon): static
    {
        $this->openAfternoon = $openAfternoon;

        return $this;
    }

    public function getCloseAfternoon(): ?string
    {
        return $this->closeAfternoon;
    }

    public function setCloseAfternoon(?string $closeAfternoon): static
    {
        $this->closeAfternoon = $closeAfternoon;

        return $this;
    }

    public function isclosed(): ?bool
    {
        return $this->isclosed;
    }

    public function setIsclosed(bool $isclosed): static
    {
        $this->isclosed = $isclosed;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
