<?php

namespace App\EventListener;

use App\Entity\Project;
use App\Service\ProjectImageFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectFormEventListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly ProjectImageFileService $fileService,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $project = $event->getData();

        if (!$project instanceof Project) {
            return;
        }

        // Get the uploaded file from the form
        $uploadedFile = $form->get('coverImageFile')->getData();

        if (!$uploadedFile instanceof UploadedFile) {
            return;
        }

        // Ensure project has an ID (for new projects, it will be set by persist)
        // For existing projects, it will already exist
        if (!$project->getId()) {
            // For new projects, we need to defer the file handling
            // Store the file temporarily in the project object
            $project->setCoverImageFile($uploadedFile);
            return;
        }

        $this->saveCoverImage($project, $uploadedFile);
    }

    public function saveCoverImage(Project $project, UploadedFile $uploadedFile): void
    {
        try {
            $projectId = $project->getId();
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

            $project->setCoverImage('assets/project_' . $projectId . '/' . $filename);
        } catch (\Exception $e) {
            error_log('Failed to upload cover image: ' . $e->getMessage());
        }
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }
    }
}
