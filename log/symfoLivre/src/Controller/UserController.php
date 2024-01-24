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

    /**
     * @Route("/user_new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez persist pour préparer l'entité à être persistée
            $em->persist($user);
            // Utilisez flush pour effectivement enregistrer l'entité en base de données
            $em->flush();
    
            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('user/nouveau.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'users_sup', methods:['POST'])]
    public function suppr(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $manager): Response
        {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {        
            $manager->remove($user);
            
            //$manager= $this->getDoctrine()-getManager();
                
                //$this->$manager->remove(User);
            $manager->flush();
        }
        return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
    }

}
