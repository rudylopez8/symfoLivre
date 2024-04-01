<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\UserAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Security;


//#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
        }
  return $this->render('user/erreur.html.twig');
    }
    #[Route('/userTestAuteur', name: 'app_user_testAuteur', methods: ['GET'])]
    public function testAuteur(UserRepository $userRepository): Response
    {
        if ($this->isGranted('ROLE_AUTOR')) {
        return $this->render('user/testAuteur.html.twig', []);
        }
  return $this->render('user/erreur.html.twig');
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
        }
  return $this->render('user/erreur.html.twig');
    }
    #[Route('user_profile', name: 'app_user_profile', methods: ['GET'])]
    public function showProfile(Security $security): Response
    {
        $user = $security->getUser();
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
        }
  return $this->render('user/erreur.html.twig');
    }

    #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_USER')) {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
        //rôle du formulaire
//        $roles = [$form->get('roles')->getData()];
//        $user->setRoles([$roles]);
//$user->addRole([$form->get('roles')->getData()]);

        $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
        }
  return $this->render('user/erreur.html.twig');
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN') || $currentUser === $user) {
//        if ($this->isGranted('ROLE_ADMIN')) {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
  return $this->render('user/erreur.html.twig');
    }
}
