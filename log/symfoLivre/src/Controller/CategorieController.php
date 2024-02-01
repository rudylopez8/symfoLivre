<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategorieType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    
    #[Route('/categorie', name: 'app_categorie')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/categorie_new', name: 'categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRepository $categorieRepository, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Utilisez persist pour préparer l'entité à être persistée
            $em->persist($categorie);
            // Utilisez flush pour effectivement enregistrer l'entité en base de données
            $em->flush();
    
            return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('categorie/nouveau.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

      #[Route('/{id}/categorie_edit', name: 'categorie_edit', methods: ['POST'])]
      public function edit( EntityManagerInterface $em, Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
      {
          $form = $this->createForm(CategorieType::class, $categorie);
          $form->handleRequest($request);
  
          if ($form->isSubmitted() && $form->isValid()) {
              
              //$categorieRepository->add($categorie, true);
              $em->persist($categorie);
              $em->flush();
              return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
          }
  
          return $this->renderForm('categorie/edit.html.twig', [
              'categorie' => $categorie,
              'form' => $form,
          ]);
     } 
  
    #[Route('/{id}/cate_sup', name: 'cate_sup', methods:['POST'])]
    public function suppr(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, EntityManagerInterface $manager): Response
        {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {        
            $manager->remove($categorie);
            
            //$manager= $this->getDoctrine()-getManager();
                
                //$this->$manager->remove(Categorie);
            $manager->flush();
        }
        return $this->redirectToRoute('app_categorie', [], Response::HTTP_SEE_OTHER);
    }
}
