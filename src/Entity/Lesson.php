<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LessonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
#[ApiResource]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Material = null;

    #[ORM\Column(length: 50)]
    private ?string $Duration = null;

    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy: 'lessons')]
    private Collection $Course;

    #[ORM\ManyToMany(targetEntity: Feedback::class, mappedBy: 'lessonId')]
    private Collection $feedback;

    public function __construct()
    {
        $this->Course = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->Material;
    }

    public function setMaterial(string $Material): static
    {
        $this->Material = $Material;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->Duration;
    }

    public function setDuration(string $Duration): static
    {
        $this->Duration = $Duration;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourse(): Collection
    {
        return $this->Course;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->Course->contains($course)) {
            $this->Course->add($course);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        $this->Course->removeElement($course);

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->addLessonId($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            $feedback->removeLessonId($this);
        }

        return $this;
    }
}
