<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Vous devez saisir un titre.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Vous devez saisir du contenu.')]
    private ?string $content = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private ?bool $done = false;

    public function getId(): ?int {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): static {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): static {
        $this->content = $content;

        return $this;
    }

    public function isDone(): ?bool {
        return $this->done;
    }

    public function setDone(bool $done): static {
        $this->done = $done;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void {
        $this->createdAt = $this->createdAt ?? new DateTimeImmutable();
    }
}
