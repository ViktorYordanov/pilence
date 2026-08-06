<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'project')]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'title_bg', length: 255)]
    private ?string $titleBg = null;

    #[ORM\Column(name: 'title_en', length: 255)]
    private ?string $titleEn = null;

    #[ORM\Column(name: 'description_bg', type: Types::TEXT)]
    private ?string $descriptionBg = null;

    #[ORM\Column(name: 'description_en', type: Types::TEXT)]
    private ?string $descriptionEn = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $year = null;

    #[ORM\Column(name: 'cover_image', length: 255, nullable: true)]
    private ?string $coverImage = null;

    #[ORM\Column(name: 'created_at')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'updated_at')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, ProjectTag>
     */
    #[ORM\OneToMany(targetEntity: ProjectTag::class, mappedBy: 'project', cascade: ['all'], orphanRemoval: true)]
    private Collection $projectTags;

    /**
     * @var Collection<int, Tool>
     */
    #[ORM\ManyToMany(targetEntity: Tool::class, inversedBy: 'projects')]
    #[ORM\JoinTable(name: 'project_tool')]
    private Collection $tools;

    /**
     * @var Collection<int, ProjectImage>
     */
    #[ORM\OneToMany(targetEntity: ProjectImage::class, mappedBy: 'project', cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $images;

    public function __construct()
    {
        $this->projectTags = new ArrayCollection();
        $this->tools = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleBg(): ?string
    {
        return $this->titleBg;
    }

    public function setTitleBg(string $titleBg): static
    {
        $this->titleBg = $titleBg;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(string $titleEn): static
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getDescriptionBg(): ?string
    {
        return $this->descriptionBg;
    }

    public function setDescriptionBg(string $descriptionBg): static
    {
        $this->descriptionBg = $descriptionBg;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(string $descriptionEn): static
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ProjectTag>
     */
    public function getProjectTags(): Collection
    {
        return $this->projectTags;
    }

    public function addProjectTag(ProjectTag $projectTag): static
    {
        if (!$this->projectTags->contains($projectTag)) {
            $this->projectTags->add($projectTag);
            $projectTag->setProject($this);
        }

        return $this;
    }

    public function removeProjectTag(ProjectTag $projectTag): static
    {
        if ($this->projectTags->removeElement($projectTag)) {
            if ($projectTag->getProject() === $this) {
                $projectTag->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tool>
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(Tool $tool): static
    {
        if (!$this->tools->contains($tool)) {
            $this->tools->add($tool);
        }

        return $this;
    }

    public function removeTool(Tool $tool): static
    {
        $this->tools->removeElement($tool);

        return $this;
    }

    /**
     * @return Collection<int, ProjectImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProjectImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProject($this);
        }

        return $this;
    }

    public function removeImage(ProjectImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getProject() === $this) {
                $image->setProject(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->titleBg ?? 'Project';
    }
}
