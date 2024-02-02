<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // Créez une nouvelle instance de l'entité User
        $user = new User();

        // Créez le formulaire d'inscription en utilisant la classe de formulaire associée
        $form = $this->createForm(RegistrationType::class, $user);

        // Gérez la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodage du mot de passe avant de le sauvegarder dans la base de données
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Sauvegardez l'utilisateur dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirigez l'utilisateur vers une page après l'inscription (par exemple, la page d'accueil)
            return $this->redirectToRoute('app_home');
        }

        // Affichez le formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
