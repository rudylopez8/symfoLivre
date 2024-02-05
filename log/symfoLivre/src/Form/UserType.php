<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $passwordHasher = $options['password_hasher'];
        $builder
            ->add('nomUser')
            ->add('mailUser')
            ->add('passwordUser')
            ->add('roleUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_hasher' => null,
        ]);

        $resolver->setAllowedTypes('password_hasher', [UserPasswordHasherInterface::class, 'null']);
    }

//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => User::class,
//        ]);
//    }
}
