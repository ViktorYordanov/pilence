<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.tag.singular')
            ->setEntityLabelInPlural('admin.tag.plural')
            ->setPageTitle('index', 'admin.tag.list')
            ->setPageTitle('new', 'admin.tag.create')
            ->setPageTitle('edit', 'admin.tag.edit')
            ->setDefaultSort(['nameBg' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('slug')
            ->setLabel('admin.tag.slug')
            ->setHelp('admin.tag.slug_help')
            ->setFormTypeOptions(['disabled' => 'edit' === $pageName]);

        yield TextField::new('nameBg')
            ->setLabel('admin.tag.name_bg')
            ->setHelp('admin.tag.name_bg_help');

        yield TextField::new('nameEn')
            ->setLabel('admin.tag.name_en')
            ->setHelp('admin.tag.name_en_help');

        if ('detail' === $pageName || 'show' === $pageName) {
            yield DateTimeField::new('createdAt')
                ->setLabel('admin.common.created_at');
        }
    }
}
