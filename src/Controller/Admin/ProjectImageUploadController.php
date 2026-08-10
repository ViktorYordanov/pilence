<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectImage;
use App\Service\ProjectImageFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/projects/{id}/images/upload', name: 'admin_projects_images_upload', methods: ['POST'], requirements: ['id' => '\d+'])]
class ProjectImageUploadController extends AbstractController
{
    public function __construct(
        private readonly ProjectImageFileService $fileService,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Project $project, Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return $this->json(['success' => false, 'message' => 'No file provided'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $image = new ProjectImage();
            $image->setProject($project);
            $image->setTitleBg($request->request->get('titleBg'));
            $image->setTitleEn($request->request->get('titleEn'));
            $image->setPosition((int)$request->request->get('position', $project->getImages()->count() + 1));
            $image->setIsVisible($request->request->get('isVisible', 'true') === 'true');

            $this->fileService->storeUploadedFile($image, $file);
            $this->em->persist($image);
            $this->em->flush();

            return $this->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image' => [
                    'id' => $image->getId(),
                    'imagePath' => $image->getImagePath(),
                    'titleBg' => $image->getTitleBg(),
                    'titleEn' => $image->getTitleEn(),
                    'position' => $image->getPosition(),
                    'isVisible' => $image->isIsVisible(),
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
