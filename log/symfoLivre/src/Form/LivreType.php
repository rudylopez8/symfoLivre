<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreLivre')
            ->add('fichierLivre')
            ->add('resumeLivre')
            ->add('prixLivre')
            ->add('dateUploadLivre')
            ->add('auteurLivre', EntityType::class, [
                'class' => 'App\Entity\User', // le chemin réel de Categorie
                'choice_label' => 'nomUser', // le champ à afficher
                'multiple' => false,
                'expanded' => false,
            ])

            ->add('categorieLivre', EntityType::class, [
                'class' => 'App\Entity\Categorie', // le chemin réel de Categorie
                'choice_label' => 'nomCategorie', // le champ à afficher
                'multiple' => false,
                'expanded' => false,
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
