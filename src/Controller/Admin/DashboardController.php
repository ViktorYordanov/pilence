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
            ->setTitle('admin.dashboard.title')
            // Renders EasyAdmin's built-in language switcher in the user menu.
            ->setLocales(['bg', 'en'])
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('admin.menu.dashboard', 'fa fa-home');

        yield MenuItem::section('admin.menu.portfolio');
        yield MenuItem::linkToCrud('admin.menu.projects', 'fa fa-briefcase', \App\Entity\Project::class);
        yield MenuItem::linkToCrud('admin.menu.tools', 'fa fa-wrench', \App\Entity\Tool::class);
        yield MenuItem::linkToCrud('admin.menu.project_images', 'fa fa-image', \App\Entity\ProjectImage::class);
        yield MenuItem::linkToCrud('admin.menu.project_tags', 'fa fa-tag', \App\Entity\ProjectTag::class);

        yield MenuItem::section();
        yield MenuItem::linkToUrl('admin.menu.back_to_site', 'fa fa-arrow-left', '/');
        yield MenuItem::linkToLogout('admin.menu.logout', 'fa fa-sign-out');
    }
}
