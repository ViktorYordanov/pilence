<?php

namespace App\Controller\Admin;

use App\Entity\ProjectTag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ProjectTagCrudController extends AbstractCrudController
{
    private const TAGS = [
        'logo_design',
        'brand_identity',
        'print_material',
        'social_media',
        'product_design',
        'digital_illustration',
    ];

    public static function getEntityFqcn(): string
    {
        return ProjectTag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.project_tag.singular')
            ->setEntityLabelInPlural('admin.project_tag.plural')
            ->setPageTitle('index', 'admin.project_tag.list')
            ->setPageTitle('new', 'admin.project_tag.create')
            ->setPageTitle('edit', 'admin.project_tag.edit');
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('project')
            ->setLabel('admin.project_tag.project');

        yield ChoiceField::new('tagSlug')
            ->setLabel('admin.project_tag.tag_slug')
            ->setChoices(array_combine(self::TAGS, self::TAGS));
    }
}
