<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[UniqueEntity('email')]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Hiba! Kérjük e-mail címet adjál meg!',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
