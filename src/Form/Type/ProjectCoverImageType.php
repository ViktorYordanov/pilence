<?php

namespace App\Form\Type;

use App\Entity\Project;
use App\Service\ProjectImageFileService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectCoverImageType extends AbstractType
{
    public function __construct(private readonly ProjectImageFileService $fileService)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'], 10);
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $uploadedFile = $event->getData();

        if (!$uploadedFile instanceof UploadedFile) {
            return;
        }

        $parentForm = $event->getForm()->getParent();
        if (!$parentForm) {
            return;
        }

        $project = $parentForm->getData();
        if (!$project instanceof Project) {
            return;
        }

        // Defer the actual file save to post-persist listener
        // Store the file temporarily on the project
        if (!$project->getId()) {
            // For new projects, store temporarily - post-persist will handle it
            $reflection = new \ReflectionClass($project);
            $property = $reflection->getProperty('coverImageUpload');
            $property->setAccessible(true);
            $property->setValue($project, $uploadedFile);
        } else {
            // For existing projects, save immediately
            $this->saveCoverImage($project, $uploadedFile);
        }
    }

    private function saveCoverImage(Project $project, UploadedFile $uploadedFile): void
    {
        try {
            $projectId = $project->getId();
            if (!$projectId) {
                return;
            }

            $projectDir = $this->fileService->getProjectImageDirectory($projectId);
            if (!is_dir($projectDir)) {
                @mkdir($projectDir, 0755, true);
            }

            $filename = 'cover_image.png';
            $coverImagePath = $projectDir . '/' . $filename;

            if (file_exists($coverImagePath)) {
                unlink($coverImagePath);
            }

            $uploadedFile->move($projectDir, $filename);
            $project->setCoverImage('assets/project_' . $projectId . '/' . $filename);
        } catch (\Exception $e) {
            error_log('Failed to save cover image: ' . $e->getMessage());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'mapped' => false,
        ]);
    }

    public function getParent(): ?string
    {
        return FileType::class;
    }
}
