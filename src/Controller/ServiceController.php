<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/services/{slug}', name: 'app_service')]
    public function show(string $slug): Response
    {
        $services = [
            'logo-design' => [
                'title_bg' => 'Дизайн на логото',
                'title_en' => 'Logo Design',
                'subtitle' => 'Professional branding identity',
            ],
            'brand-identity' => [
                'title_bg' => 'Фирмена идентичност',
                'title_en' => 'Brand Identity',
                'subtitle' => 'Complete visual system',
            ],
            'print-material' => [
                'title_bg' => 'Печатни материали',
                'title_en' => 'Print Material',
                'subtitle' => 'Tangible brand experiences',
            ],
            'social-media' => [
                'title_bg' => 'Социални мрежи',
                'title_en' => 'Social Media',
                'subtitle' => 'Digital presence optimization',
            ],
            'product-design' => [
                'title_bg' => 'Дизайн на продукти',
                'title_en' => 'Product Design',
                'subtitle' => 'Innovation meets aesthetics',
            ],
            'digital-illustration' => [
                'title_bg' => 'Дигитална илюстрация',
                'title_en' => 'Digital Illustration',
                'subtitle' => 'Custom visual storytelling',
            ],
        ];

        if (!isset($services[$slug])) {
            throw $this->createNotFoundException('Service not found');
        }

        $service = $services[$slug];

        return $this->render('service/show.html.twig', [
            'slug' => $slug,
            'service' => $service,
        ]);
    }
}
