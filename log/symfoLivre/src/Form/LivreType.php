<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
                'class' => 'App\Entity\User', // le chemin réel de User
                'choice_label' => 'nomUser', // le champ à afficher
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('categorieLivre', EntityType::class, [
                // Label du champ    
                'label'  => 'Categorie',
                'placeholder' => 'Categorie',
        
                // looks for choices from this entity
                'class' => Categorie::class,
            
                // Sur quelle propriete je fais le choix
                'choice_label' => 'nomCategorie',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                //'expanded' => true,
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
