<?php

namespace App\EventSubscriber;

use App\Entity\Project;
use App\Service\ProjectImageFileService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::preRemove)]
class ProjectDeletionSubscriber
{
    public function __construct(private readonly ProjectImageFileService $fileService)
    {
    }

    public function preRemove(PreRemoveEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Project) {
            return;
        }

        $projectId = $entity->getId();
        if (!$projectId) {
            return;
        }

        foreach ($entity->getImages() as $image) {
            try {
                $this->fileService->deleteImageFile($image);
            } catch (\Exception $e) {
                // Log error but continue deletion process
                error_log('Failed to delete image file: ' . $e->getMessage());
            }
        }

        try {
            $this->fileService->deleteProjectDirectory($projectId);
        } catch (\Exception $e) {
            // Log error but don't block project deletion
            error_log('Failed to delete project directory: ' . $e->getMessage());
        }
    }
}
