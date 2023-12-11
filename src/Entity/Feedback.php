<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
#[ApiResource]
#[ORM\Entity(repositoryClass: FeedbackRepository::class)]

class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'feedback')]
    private Collection $userId;

    #[ORM\ManyToMany(targetEntity: Lesson::class, inversedBy: 'feedback')]
    private Collection $lessonId;

    #[ORM\Column(length: 30)]
    private ?string $date = null;

    public function __construct()
    {
        $this->userId = new ArrayCollection();
        $this->lessonId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->userId;
    }

    public function addUserId(User $userId): static
    {
        if (!$this->userId->contains($userId)) {
            $this->userId->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): static
    {
        $this->userId->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, Lesson>
     */
    public function getLessonId(): Collection
    {
        return $this->lessonId;
    }

    public function addLessonId(Lesson $lessonId): static
    {
        if (!$this->lessonId->contains($lessonId)) {
            $this->lessonId->add($lessonId);
        }

        return $this;
    }

    public function removeLessonId(Lesson $lessonId): static
    {
        $this->lessonId->removeElement($lessonId);

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }
}
