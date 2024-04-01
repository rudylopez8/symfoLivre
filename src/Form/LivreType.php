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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

//class LivreType extends AbstractType
class LivreType extends AbstractType implements EventSubscriberInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreLivre')            
            ->add('fichierLivre', FileType::class, [
                'label' => 'Sélectionnez un fichier (zip ou 7zip)',
                'required' => true,
                'constraints' => $options['file_constraints'],
            ])
//    ->add('fichierLivre', TextType::class, [
//        'label' => 'Sélectionnez un fichier (zip ou 7zip)',
//        'required' => true,
//    ])                                  
            ->add('resumeLivre')
//            ->add('prixLivre')
            ->add('prixLivre', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'step' => '0.01', // Définissez la précision souhaitée
                    'min' => '0.00',  // Définissez la valeur minimale si nécessaire
                ],
            ])
            ->add('dateUploadLivre')
//            ->add('auteurLivre', EntityType::class, [
//                'class' => 'App\Entity\User',
//                'choice_label' => 'nomUser',
//                'multiple' => false,
//                'expanded' => false,
//            ])
            ->add('categorieLivre', EntityType::class, [
                'label' => 'Categorie',
                'placeholder' => 'Categorie',
                'class' => Categorie::class,
                'choice_label' => 'nomCategorie',
            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $livre = $event->getData();

        // Si le Livre a un fichier, changez le champ en un champ texte au lieu d'un champ fichier
        if ($livre && $livre->getFichierLivre()) {
            $form->add('fichierLivre', TextType::class, [
                'label' => 'Nom du fichier',
                'required' => false,
                'mapped' => false, // Ne pas mapper ce champ à l'entité
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
            'file_constraints' => [],
        ]);
    }
}
