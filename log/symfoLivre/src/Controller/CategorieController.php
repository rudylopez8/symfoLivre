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
            'cats' => $categorieRepository->findAll(),
        ]);
    }

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
    
            return $this->redirectToRoute('categorie', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('categorie/nouveau.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

}
