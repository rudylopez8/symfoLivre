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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
                $user->setPassword($form->get('password')->getData());
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
    /**
     * @Route("/login", name="app_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, le rediriger vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recherche de l'utilisateur en base de données
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $existingUser = $userRepository->findOneBy(['mailUser' => $user->getMailUser()]);

            if (!$existingUser || !$this->get('security.password_encoder')->isPasswordValid($existingUser, $user->getPasswordUser())) {
                // Erreur : mauvais email ou mot de passe
                $this->addFlash('error', 'Identifiants incorrects.');
                return $this->redirectToRoute('app_login');
            }

            // Création d'un token d'authentification
            $token = new UsernamePasswordToken($existingUser, null, 'main', $existingUser->getRoles());

            // Authentification de l'utilisateur
            $this->get('security.token_storage')->setToken($token);

            // Redirection vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'form'  => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }
        /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        // La fonction de déconnexion est gérée automatiquement par Symfony
    }
}
