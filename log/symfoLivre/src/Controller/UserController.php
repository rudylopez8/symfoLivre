<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }


    public function new(Request $request, UserRepository $UserRepository, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $UserRepository->add($user, true);
          
           //$em->persist($user);
           //$em->flush();
           
           return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/nouveau.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }



    #[Route('/u/nouv', name: 'utilisateur.nouv')]
    public function nouvelUtilisateur(Request $request, EntityManagerInterface $manager)
    {
        $user = new User(); // user vide pret à etre rempli
        $form = $this->createForm(UserType::class, $user);  // Creation du Form qui est lié à mon user

        $form->handleRequest($request);         // Le Request

        if ($form->isSubMitted() && $form->isValid()) // Soumission du Formulaire & le Formulaire est valide
        {
            $manager->persist($user); // Persistancede mon user
            $manager->flush(); // Enregistrement de user dans la BD 
            return $this->redirectToRoute('user'); // Redirection vers l'affichage
        }

        return $this->render('user/index.html.twig', [
            'formUtilisateur' => $form->createView()
        ]); // On ne va pas passer à Twig $form parce que tres lourd et difficile 
        //On passe ici à twig une variable facile à Afficher
        // Twig va donc avoir le resultat de la Function CreateView du formulaire. C'est l'asspect Affichage que nou spassons au formulaire
    }

}
