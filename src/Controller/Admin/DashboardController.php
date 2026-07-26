<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Simple welcome page. Once you generate CRUD controllers for your
        // content entities, you can redirect straight to one of them instead, e.g.:
        //
        //     return $this->redirect(
        //         $this->container->get(AdminUrlGenerator::class)
        //             ->setController(SomeCrudController::class)
        //             ->generateUrl()
        //     );
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pilence Admin')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Add one entry per content type as you create its CRUD controller, e.g.:
        // yield MenuItem::section('Content');
        // yield MenuItem::linkToCrud('Pages', 'fa fa-file-lines', Page::class);
        // yield MenuItem::linkToCrud('Blog posts', 'fa fa-newspaper', Post::class);

        yield MenuItem::section();
        yield MenuItem::linkToUrl('Back to website', 'fa fa-arrow-left', '/');
        yield MenuItem::linkToLogout('Log out', 'fa fa-sign-out');
    }
}
