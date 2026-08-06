<?php

namespace App\Controller\Admin;

use App\Entity\ProjectImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectImage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.project_image.singular')
            ->setEntityLabelInPlural('admin.project_image.plural')
            ->setPageTitle('index', 'admin.project_image.list')
            ->setPageTitle('new', 'admin.project_image.create')
            ->setPageTitle('edit', 'admin.project_image.edit')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('project')
            ->setLabel('admin.project_image.project')
            ->setRequired(true);

        yield TextField::new('imagePath')
            ->setLabel('admin.project_image.image_path')
            ->setHelp('admin.project_image.image_path_help');

        yield TextField::new('titleBg')
            ->setLabel('admin.project_image.title_bg')
            ->setRequired(false);

        yield TextField::new('titleEn')
            ->setLabel('admin.project_image.title_en')
            ->setRequired(false);

        yield IntegerField::new('position')
            ->setLabel('admin.project_image.position')
            ->setHelp('admin.project_image.position_help');

        yield BooleanField::new('isVisible')
            ->setLabel('admin.project_image.is_visible');
    }
}
