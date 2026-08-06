<?php

namespace App\Entity;

use App\Repository\ProjectTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectTagRepository::class)]
#[ORM\Table(name: 'project_tag')]
#[ORM\UniqueConstraint(name: 'UNIQ_PROJECT_TAG', fields: ['project_id', 'tag_slug'])]
class ProjectTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(name: 'tag_slug', length: 255)]
    private ?string $tagSlug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTagSlug(): ?string
    {
        return $this->tagSlug;
    }

    public function setTagSlug(string $tagSlug): static
    {
        $this->tagSlug = $tagSlug;

        return $this;
    }

    public function __toString(): string
    {
        return $this->tagSlug ?? '';
    }
}
