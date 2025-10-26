<?php

namespace App\Entity;

use App\Enum\ThemesEnum;
use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank(message: 'Le titre ne peut être vide')]
    #[Length(min:1, max: 40, minMessage: 'Le titre doit comporté au minimum 1 caracètre', maxMessage: 'Le titre ne peut comporter plus de 40 caractères')]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank(message: 'La description ne peut être vide')]
    #[Length(min:1, max: 255, minMessage: 'La description doit comporté au minimum 1 caracètre', maxMessage: 'La description ne peut comporter plus de 255 caractères')]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank(message: 'Le mail de contact ne peut être vide')]
    #[Email(message: 'Le mail doit être correct')]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Length(exactly: 10, exactMessage: 'Le numéro de téléphone doit être composer de 10 chiffres')]
    #[Regex('/^(0[67](?:\d{2}[- ]?){4})$/', message: 'Veuillez saisir un numéro de téléphone valide')]
    private ?string $contactPhone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $favicon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(enumType: ThemesEnum::class)]
    private ?ThemesEnum $theme = null;


    #[ORM\PrePersist]
    function persist() {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    function flush() {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFavicon(): ?string
    {
        return $this->favicon;
    }

    public function setFavicon(?string $favicon): static
    {
        $this->favicon = $favicon;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTheme(): ?ThemesEnum
    {
        return $this->theme;
    }

    public function setTheme(ThemesEnum $theme): static
    {
        $this->theme = $theme;

        return $this;
    }
}
