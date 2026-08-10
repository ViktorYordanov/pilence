<?php

namespace App\Entity;

use App\Repository\ProjectTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectTagRepository::class)]
#[ORM\Table(name: 'project_tag')]
#[ORM\UniqueConstraint(name: 'UNIQ_PROJECT_TAG', fields: ['project_id', 'tag_id'])]
class ProjectTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'projectTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $tag = null;

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

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function __toString(): string
    {
        return $this->tag?->getNameBg() ?? '';
    }
}
