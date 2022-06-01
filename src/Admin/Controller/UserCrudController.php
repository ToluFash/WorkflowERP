<?php

namespace App\Admin\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        FormField::addPanel('User Details');
        yield 'firstName';
        yield 'lastName';
        yield 'username';
        yield 'email';
        yield 'phone';
        yield ChoiceField::new('sex')->setChoices([
            'Male' => 1,
            'Female' => 2
        ]);
        FormField::addPanel('Verification');
        yield 'isVerified';
        FormField::addPanel('Roles');
        yield ChoiceField::new('roles')->allowMultipleChoices()->setChoices(User::ROLES);
    }
}
