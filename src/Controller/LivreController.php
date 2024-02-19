<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LivreType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\UploadFileType;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/newLivre', name: 'app_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
    
        // Continuez avec le formulaire principal
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fichierLivre')->getData();
    
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/dataLivre';
            $file->move($uploadsDirectory, $file->getClientOriginalName());
            $this->addFlash('success', 'Le fichier a été téléversé avec succès.');
    
            $livre->setFichierLivre($file->getClientOriginalName());
    
            $entityManager->persist($livre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_livre', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }
        

    #[Route('/uploadLivre', name: 'app_uploadLivre', methods: ['GET', 'POST'])]
    public function uploadFile(Request $request): Response
    {
//        dd('uploadFile called');
        $form = $this->createForm(UploadFileType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            // Gérez le téléversement du fichier ici
            // Exemple : déplacez le fichier vers un dossier spécifique
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/dataLivre';
            $file->move($uploadsDirectory, $file->getClientOriginalName());

            // Ajoutez d'autres traitements si nécessaire

            $this->addFlash('success', 'Le fichier a été téléversé avec succès.');
            return $this->redirectToRoute('app_uploadLivre');
        }

        return $this->render('livre/testUpload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/livre_show', name: 'livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }
    
    #[Route('/{id}/app_livre_edit', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
    
        // Vérifiez si la requête est de type POST
        if ($request->isMethod('POST')) {
            // Récupérez le fichier soumis
            $nouveauFichier = $request->files->get('livre')->getFichierLivre();

            // Si un nouveau fichier a été soumis
            if ($nouveauFichier) {
                // Supprimer le fichier précédent s'il existe
                $ancienFichier = $livre->getFichierLivre();
                $ancienChemin = $this->getParameter('kernel.project_dir') . '/public/dataLivre/' . $ancienFichier;
    
                if (file_exists($ancienChemin)) {
                    unlink($ancienChemin);
                }
    
                // Déplacez le nouveau fichier vers le répertoire approprié
                $nouveauFichier->move($this->getParameter('kernel.project_dir') . '/public/dataLivre', $nouveauFichier->getClientOriginalName());
    
                // Mettez à jour la propriété fichierLivre avec le nouveau nom de fichier
                $livre->setFichierLivre($nouveauFichier->getClientOriginalName());
            }
    
            // Remplissez le formulaire avec les données mises à jour
        $form->submit($request->request->get('livre'));
    
            // Vérifiez si le formulaire est valide avant de mettre à jour l'entité
            if ($form->isValid()) {
                $entityManager->flush();
    
                return $this->redirectToRoute('app_livre', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }
                
    #[Route('/{id}/livre_sup', name: 'livre_sup', methods:['POST'])]
    public function suppr(Request $request, Livre $livre, LivreRepository $livreRepository, EntityManagerInterface $manager): Response
        {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {        
            $manager->remove($livre);
            
            //$manager= $this->getDoctrine()-getManager();
                
                //$this->$manager->remove(Livre);
            $manager->flush();
        }
        return $this->redirectToRoute('app_livre', [], Response::HTTP_SEE_OTHER);
    }

}
