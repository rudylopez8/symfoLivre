<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User($passwordHasher); // Fournir le service UserPasswordHasherInterface ici

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe avant de le sauvegarder dans la base de données
                $user->setPasswordUser($form->get('passwordUser')->getData());
                       // Utilisez persist pour préparer l'entité à être persistée
                       $em->persist($user);
                       // Utilisez flush pour effectivement enregistrer l'entité en base de données
                       $em->flush();
           


            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
