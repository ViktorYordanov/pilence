<?php

namespace App\Service;

use App\Entity\ProjectImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProjectImageFileService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    public function __construct(private readonly string $projectsAssetsDir)
    {
    }

    public function storeUploadedFile(ProjectImage $image, UploadedFile $file): void
    {
        $this->validateFile($file);

        $projectId = $image->getProject()?->getId();
        if (!$projectId) {
            throw new \RuntimeException('ProjectImage must be associated with a Project before uploading');
        }

        $projectDir = $this->getProjectImageDirectory($projectId);
        $this->ensureDirectoryExists($projectDir);

        $extension = $this->getFileExtensionFromMimeType($file->getMimeType())
            ?? strtolower($file->guessExtension() ?? 'jpg');

        $filename = bin2hex(random_bytes(8)) . '.' . $extension;

        try {
            $file->move($projectDir, $filename);
        } catch (FileException $e) {
            throw new \RuntimeException('Failed to save uploaded file: ' . $e->getMessage(), 0, $e);
        }

        $image->setImagePath('assets/project_' . $projectId . '/' . $filename);
        $image->setFileExtension($extension);
    }

    public function deleteImageFile(ProjectImage $image): void
    {
        $imagePath = $image->getImagePath();
        if (!$imagePath) {
            return;
        }

        $fullPath = $this->projectsAssetsDir . '/' . $imagePath;

        try {
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete image file: ' . $e->getMessage(), 0, $e);
        }
    }

    public function deleteProjectDirectory(int $projectId): void
    {
        $projectDir = $this->getProjectImageDirectory($projectId);

        try {
            if (is_dir($projectDir)) {
                $this->removeDirectory($projectDir);
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to delete project directory: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getProjectImageDirectory(int $projectId): string
    {
        return $this->projectsAssetsDir . '/project_' . $projectId;
    }

    private function validateFile(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException('File exceeds maximum size of 10MB');
        }

        $mimeType = $file->getMimeType();
        if (!isset(self::ALLOWED_MIME_TYPES[$mimeType])) {
            throw new \InvalidArgumentException('File type not allowed. Allowed types: jpg, jpeg, png, gif, webp');
        }
    }

    private function getFileExtensionFromMimeType(string $mimeType): ?string
    {
        return self::ALLOWED_MIME_TYPES[$mimeType] ?? null;
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            if (!@mkdir($directory, 0755, true)) {
                throw new \RuntimeException('Failed to create directory: ' . $directory);
            }
        }
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = array_diff(scandir($directory), ['.', '..']);
        foreach ($files as $file) {
            $path = $directory . '/' . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($directory);
    }
}
