<?php

namespace App\EventSubscriber;

use App\Entity\Project;
use App\Service\ProjectImageFileService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
class ProjectPostPersistSubscriber
{
    public function __construct(private readonly ProjectImageFileService $fileService)
    {
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->handleCoverImage($args->getObject());
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->handleCoverImage($args->getObject());
    }

    private function handleCoverImage(mixed $entity): void
    {
        if (!$entity instanceof Project) {
            return;
        }

        // Check if there's a temporary file stored on the entity
        if (!method_exists($entity, 'getCoverImageFile')) {
            return;
        }

        $uploadedFile = $entity->getCoverImageFile();

        if (!$uploadedFile instanceof UploadedFile) {
            return;
        }

        try {
            $projectId = $entity->getId();
            if (!$projectId) {
                return;
            }

            $projectDir = $this->fileService->getProjectImageDirectory($projectId);
            $this->ensureDirectoryExists($projectDir);

            $filename = 'cover_image.png';
            $coverImagePath = $projectDir . '/' . $filename;

            // Delete old cover image if it exists
            if (file_exists($coverImagePath)) {
                unlink($coverImagePath);
            }

            // Save the new cover image
            $uploadedFile->move($projectDir, $filename);

            $entity->setCoverImage('assets/project_' . $projectId . '/' . $filename);

            // Clear the temporary file
            $entity->setCoverImageFile(null);
        } catch (\Exception $e) {
            error_log('Failed to save cover image: ' . $e->getMessage());
        }
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}
