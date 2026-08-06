<?php

namespace App\Controller\Admin;

use App\Entity\Tool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('admin.tool.singular')
            ->setEntityLabelInPlural('admin.tool.plural')
            ->setPageTitle('index', 'admin.tool.list')
            ->setPageTitle('new', 'admin.tool.create')
            ->setPageTitle('edit', 'admin.tool.edit');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nameBg')
            ->setLabel('admin.tool.name_bg')
            ->setHelp('admin.tool.name_help');

        yield TextField::new('nameEn')
            ->setLabel('admin.tool.name_en')
            ->setHelp('admin.tool.name_help');

        yield TextField::new('icon')
            ->setLabel('admin.tool.icon')
            ->setHelp('admin.tool.icon_help')
            ->setRequired(false);

        yield AssociationField::new('projects')
            ->setLabel('admin.tool.projects');
    }
}
