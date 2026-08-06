<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
    }

    #[Route('/project/{id}', name: 'app_project', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            throw new NotFoundHttpException('Project not found');
        }

        $locale = $this->getParameter('app.locale') ?? 'bg';
        $titleField = 'title_' . $this->getRequest()->getLocale();
        $descriptionField = 'description_' . $this->getRequest()->getLocale();

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'title_field' => $titleField,
            'description_field' => $descriptionField,
        ]);
    }
}
