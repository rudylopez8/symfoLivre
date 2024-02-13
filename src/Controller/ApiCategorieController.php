<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categorie')]
class ApiCategorieController extends AbstractController
{
    #[Route('/', name: 'api_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();

        // Utilisez le Serializer pour convertir les entités en JSON
        $jsonCategories = $this->getSerializer()->normalize($categories, null, ['groups' => 'api']);

        return new JsonResponse($jsonCategories);
    }

    #[Route('/new', name: 'api_categorie_new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $categorie = new Categorie();
        $categorie->setNom($data['nom']); // Assurez-vous d'ajuster selon vos besoins

        // Utilisez le Validator pour valider l'entité
        $errors = $validator->validate($categorie);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie créée avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'api_categorie_edit', methods: ['PUT'])]
    public function edit(Request $request, Categorie $categorie, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $categorie->setNom($data['nom']); // Assurez-vous d'ajuster selon vos besoins

        // Utilisez le Validator pour valider l'entité
        $errors = $validator->validate($categorie);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie modifiée avec succès']);
    }

    #[Route('/{id}', name: 'api_categorie_delete', methods: ['DELETE'])]
    public function delete(Categorie $categorie): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categorie);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie supprimée avec succès']);
    }

    // Méthode pour obtenir le service Serializer
    private function getSerializer(): SerializerInterface
    {
        return $this->get('serializer');
    }
}
