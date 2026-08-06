<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PortfolioController extends AbstractController
{
    /**
     * The fixed tag vocabulary. These slugs are stable keys; their labels live in
     * the `portfolio.tags.*` translation entries.
     */
    private const TAGS = [
        'logo_design',
        'brand_identity',
        'print_material',
        'social_media',
        'product_design',
        'digital_illustration',
    ];

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(\App\Repository\ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAllWithRelations();

        return $this->render('portfolio/index.html.twig', [
            'tags' => self::TAGS,
            'projects' => $projects,
        ]);
    }
}
