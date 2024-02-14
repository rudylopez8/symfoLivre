<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreLivre')
            ->add('fichierLivre', FileType::class, [
                'label' => 'Sélectionnez un fichier (zip ou 7zip)',
                'required' => true,
            ])
            ->add('resumeLivre')
            ->add('prixLivre')
            ->add('dateUploadLivre')
            ->add('auteurLivre', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'nomUser',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('categorieLivre', EntityType::class, [
                'label' => 'Categorie',
                'placeholder' => 'Categorie',
                'class' => Categorie::class,
                'choice_label' => 'nomCategorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
