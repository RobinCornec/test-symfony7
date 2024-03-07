<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('id')
                ->hideOnForm(),
            IdField::new('panda_score_id'),
            TextField::new('name'),
            SlugField::new('slug')
                ->setTargetFieldName('name'),
            BooleanField::new('active'),
            DateTimeField::new('created_at')
                ->setFormat('dd/MM/Y HH:mm')
                ->hideOnForm(),
            DateTimeField::new('updated_at')
                ->setFormat('dd/MM/Y HH:mm')
                ->hideOnForm(),
        ];
    }
}
