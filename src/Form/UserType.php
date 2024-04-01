<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUser')
            ->add('email')
//            ->add('roles')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Autor' => 'ROLE_AUTOR',
//                    'Admin' => ['ROLE_AUTOR', 'ROLE_USER', 'ROLE_ADMIN'],
                    'Admin' => 'ROLE_ADMIN',
                ],
                    'multiple' => true,
                    'expanded' => false,
            ])
            ->add('password', PasswordType::class, [
                'empty_data' => '', // Définir une valeur par défaut vide pour le champ
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
//vérifier la structure de la requête POST qui est envoyée lorsque vous soumettez le formulaire d'édition.