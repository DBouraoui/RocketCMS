<?php

namespace App\Entity;

use App\Repository\ReminderPhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: ReminderPhoneRepository::class)]
class ReminderPhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Length(exactly: 10, exactMessage: 'Le numéro de téléphone doit être composer de 10 chiffres')]
    #[Regex('/^(0[67](?:\d{2}[- ]?){4})$/', message: 'Veuillez saisir un numéro de téléphone valide')]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 40, nullable: true)]
    #[Length(min: 1, max: 40, minMessage: 'Le nom ne peut être de moins d\'un carctères', maxMessage: 'Le nom est invalide')]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Length(min: 1, max: 255, minMessage: 'La raison ne peut être de moins d\'un carctères', maxMessage: 'Le nom est invalide')]
    private ?string $reason = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }
}
