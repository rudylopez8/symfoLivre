<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/categorie')]
class ApiCategorieController extends AbstractController
{
    #[Route('/', name: 'api_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository, SerializerInterface $serializer): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        $categoriesJson = $serializer->serialize($categories, 'json', ['groups' => 'api']);
        return new JsonResponse($categoriesJson, 200, [], true);
    }

    #[Route('/new', name: 'api_categorie_new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setNomCategorie($data['nom']); 
        $categorie->setInformationCategorie($data['info']); 

        $errors = $validator->validate($categorie);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie créée avec succès'], Response::HTTP_CREATED);
    }

}