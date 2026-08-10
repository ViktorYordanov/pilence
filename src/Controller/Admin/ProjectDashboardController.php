<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\ProjectImage;
use App\Repository\ProjectRepository;
use App\Repository\ProjectImageRepository;
use App\Service\ProjectImageFileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/projects', name: 'admin_projects_')]
class ProjectDashboardController extends AbstractController
{
    public function __construct(
        private readonly ProjectRepository $projectRepository,
        private readonly ProjectImageRepository $projectImageRepository,
        private readonly ProjectImageFileService $fileService,
        private readonly EntityManagerInterface $em,
    ) {
    }

    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        $projects = $this->projectRepository->findBy([], ['year' => 'DESC', 'createdAt' => 'DESC']);

        return $this->render('admin/projects_dashboard.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}/images', name: 'images', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function images(Project $project): Response
    {
        $images = $this->projectImageRepository->findByProjectId($project->getId());

        return $this->render('admin/project_images.html.twig', [
            'project' => $project,
            'images' => $images,
        ]);
    }

    #[Route('/{id}/images', name: 'images_create', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function createImage(Project $project, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $image = new ProjectImage();
            $image->setProject($project);
            $image->setTitleBg($data['titleBg'] ?? null);
            $image->setTitleEn($data['titleEn'] ?? null);
            $image->setPosition($data['position'] ?? ($project->getImages()->count() + 1));
            $image->setIsVisible($data['isVisible'] ?? true);

            $this->em->persist($image);
            $this->em->flush();

            return $this->json([
                'success' => true,
                'message' => 'Image created successfully',
                'image' => [
                    'id' => $image->getId(),
                    'titleBg' => $image->getTitleBg(),
                    'titleEn' => $image->getTitleEn(),
                    'position' => $image->getPosition(),
                    'isVisible' => $image->isIsVisible(),
                ],
            ]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/{id}/images/{imageId}', name: 'images_delete', methods: ['DELETE'], requirements: ['id' => '\d+', 'imageId' => '\d+'])]
    public function deleteImage(Project $project, ProjectImage $image): JsonResponse
    {
        if ($image->getProject()->getId() !== $project->getId()) {
            return $this->json(['success' => false, 'message' => 'Image does not belong to this project'], Response::HTTP_FORBIDDEN);
        }

        try {
            $this->fileService->deleteImageFile($image);
            $this->em->remove($image);
            $this->em->flush();

            return $this->json(['success' => true, 'message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/images/{imageId}/toggle-visibility', name: 'images_toggle_visibility', methods: ['PATCH'], requirements: ['id' => '\d+', 'imageId' => '\d+'])]
    public function toggleImageVisibility(Project $project, ProjectImage $image): JsonResponse
    {
        if ($image->getProject()->getId() !== $project->getId()) {
            return $this->json(['success' => false, 'message' => 'Image does not belong to this project'], Response::HTTP_FORBIDDEN);
        }

        try {
            $image->setIsVisible(!$image->isIsVisible());
            $this->em->flush();

            return $this->json([
                'success' => true,
                'message' => 'Visibility toggled',
                'isVisible' => $image->isIsVisible(),
            ]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function deleteProject(Project $project): JsonResponse
    {
        try {
            $this->em->remove($project);
            $this->em->flush();

            return $this->json(['success' => true, 'message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
