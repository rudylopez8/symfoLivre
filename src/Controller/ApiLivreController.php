<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/livre')]
class ApiLivreController extends AbstractController
{
    #[Route('/', name: 'api_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository, SerializerInterface $serializer): JsonResponse
    {
        $livres = $livreRepository->findAll();
//        $livresJson = $serializer->serialize($livres, 'json', ['groups' => ['apiLivre', 'apiUserDetails', 'apiDetails']]);
        $livresJson = $serializer->serialize($livres, 'json', ['groups' => 'api']);
        return new JsonResponse($livresJson, 200, [], true);
    }

}
