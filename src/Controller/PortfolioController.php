<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PortfolioController extends AbstractController
{
    /**
     * The fixed tag vocabulary. These slugs are stable keys; their labels live in
     * the `portfolio.tags.*` translation entries. Later this becomes a Tag entity/enum.
     */
    private const TAGS = [
        'logo_design',
        'brand_identity',
        'print_material',
        'social_media',
        'digital_illustration',
        'product_design',
    ];

    #[Route('/portfolio', name: 'app_portfolio')]
    public function index(): Response
    {
        // Static placeholder projects — purely for the visual layout. These will be
        // replaced by database records, where each translatable field uses the
        // agreed *_bg / *_en column convention (mirrored here by title_bg/title_en).
        $projects = [
            ['title_bg' => 'Кафене Аврора',    'title_en' => 'Aurora Café',       'media' => 1, 'tags' => ['logo_design', 'brand_identity']],
            ['title_bg' => 'Фестивал Ритъм',   'title_en' => 'Rhythm Festival',   'media' => 2, 'tags' => ['print_material', 'social_media']],
            ['title_bg' => 'Приложение Пулс',  'title_en' => 'Pulse App',         'media' => 3, 'tags' => ['product_design', 'digital_illustration']],
            ['title_bg' => 'Магазин Билкар',   'title_en' => 'Herbary Store',     'media' => 4, 'tags' => ['brand_identity', 'social_media', 'product_design']],
            ['title_bg' => 'Списание Контур',  'title_en' => 'Contour Magazine',  'media' => 5, 'tags' => ['print_material', 'digital_illustration']],
            ['title_bg' => 'Студио Норд',      'title_en' => 'Studio Nord',       'media' => 6, 'tags' => ['logo_design', 'brand_identity', 'print_material']],
            ['title_bg' => 'Клуб Волта',       'title_en' => 'Volta Club',        'media' => 1, 'tags' => ['social_media', 'digital_illustration']],
            ['title_bg' => 'Марка Феникс',     'title_en' => 'Phoenix Brand',     'media' => 2, 'tags' => ['logo_design', 'product_design']],
        ];

        return $this->render('portfolio/index.html.twig', [
            'tags' => self::TAGS,
            'projects' => $projects,
        ]);
    }
}
