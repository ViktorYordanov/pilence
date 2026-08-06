<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.project.singular')
            ->setEntityLabelInPlural('admin.project.plural')
            ->setPageTitle('index', 'admin.project.list')
            ->setPageTitle('new', 'admin.project.create')
            ->setPageTitle('edit', 'admin.project.edit')
            ->setDefaultSort(['year' => 'DESC', 'created_at' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titleBg')
            ->setLabel('admin.project.title_bg')
            ->setHelp('admin.project.title_bg_help');

        yield TextField::new('titleEn')
            ->setLabel('admin.project.title_en')
            ->setHelp('admin.project.title_en_help');

        yield TextEditorField::new('descriptionBg')
            ->setLabel('admin.project.description_bg')
            ->setHelp('admin.project.description_help');

        yield TextEditorField::new('descriptionEn')
            ->setLabel('admin.project.description_en')
            ->setHelp('admin.project.description_help');

        yield IntegerField::new('year')
            ->setLabel('admin.project.year');

        yield TextField::new('coverImage')
            ->setLabel('admin.project.cover_image')
            ->setHelp('admin.project.cover_image_help')
            ->setRequired(false);

        yield AssociationField::new('projectTags')
            ->setLabel('admin.project.tags')
            ->setHelp('admin.project.tags_help');

        yield AssociationField::new('tools')
            ->setLabel('admin.project.tools')
            ->setHelp('admin.project.tools_help');

        yield AssociationField::new('images')
            ->setLabel('admin.project.images')
            ->setHelp('admin.project.images_help');

        if ('detail' === $pageName || 'show' === $pageName) {
            yield DateTimeField::new('createdAt')->setLabel('admin.common.created_at');
            yield DateTimeField::new('updatedAt')->setLabel('admin.common.updated_at');
        }
    }
}
