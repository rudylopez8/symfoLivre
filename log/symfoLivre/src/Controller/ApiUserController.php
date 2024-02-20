<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/api/User')]
class ApiUserController extends AbstractController
{
    #[Route('/', name: 'api_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $userRepository->findAll();
        $usersJson = $serializer->serialize($users, 'json', ['groups' => 'api']);
        return new JsonResponse($usersJson, 200, [], true);
    }

    #[Route('/new', name: 'api_user_new', methods: ['POST'])]
    public function new(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setNomUser($data['nomUser']);
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);

        // Encodage du mot de passe
        $encodedPassword = $passwordEncoder->encodePassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'User créé avec succès'], Response::HTTP_CREATED);
    }
}
