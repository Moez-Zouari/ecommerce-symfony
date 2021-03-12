<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    /* public function configureFields(string $pageName): iterable
    {
       
        return [
            IdField::new('id'),
            TextField::new('email','Email'),
            TextField::new('getFullName','Utilisateur'),
            ArrayField::new('roles','Privilége'),
            ChoiceField::new('roles')->setChoices([
                'Administrateur' => '{"ROLE":"ROLE_ADMIN"}',
                'Utilisateur' => '{"ROLE":"ROLE_USER"}'])
            ->allowMultipleChoices(true)
            ->hideOnIndex(),
      
            
    
            
        ];  
    } */
    
}
