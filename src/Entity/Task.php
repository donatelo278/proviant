<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    public const STATUS_TODO = 'не выполнена';
    public const STATUS_IN_PROGRESS = 'в процессе';
    public const STATUS_DONE = 'завершена';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Название задачи обязательно")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Название должно быть не короче {{ limit }} символов",
        maxMessage: "Название должно быть не длиннее {{ limit }} символов"
    )]
    private ?string $title = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "Описание не должно превышать {{ limit }} символов"
    )]
    private ?string $description = null;

    #[ORM\Column(length: 50, options: ['default' => self::STATUS_TODO])]
    #[Assert\Choice(
        choices: [self::STATUS_TODO, self::STATUS_IN_PROGRESS, self::STATUS_DONE],
        message: "Недопустимый статус задачи"
    )]
    private ?string $status = self::STATUS_TODO;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}