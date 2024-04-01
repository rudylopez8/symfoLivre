<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\UserAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Security;



class LivreFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // Créer des utilisateurs
        $usersData = [
            ['nomUser' => 'lapin', 'email' => 'user1@gmail.com', 'password' => 'password1', 'roles' => ['ROLE_USER']],
            ['nomUser' => 'chat', 'email' => 'user2@gmail.com', 'password' => 'password2', 'roles' => ['ROLE_USER']],
            ['nomUser' => 'oiseau', 'email' => 'user3@gmail.com', 'password' => 'password3', 'roles' => ['ROLE_USER']],
            ['nomUser' => 'sourie', 'email' => 'user4@gmail.com', 'password' => 'password4', 'roles' => ['ROLE_AUTOR']],
            ['nomUser' => 'musaraigne', 'email' => 'user5@gmail.com', 'password' => 'password5', 'roles' => ['ROLE_ADMIN']],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $user = new User();
            $user
                ->setNomUser($userData['nomUser'])
                ->setEmail($userData['email'])
//                ->setPasswordAndHash($userData['password'])
                ->setPasswordAndHash($this->userPasswordHasher, $userData['password'])
                ->setRoles($userData['roles']);
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer des catégories
        $categoriesData = [
            ['nomCategorie' => 'Science-fiction', 'informationCategorie' => 'Livres de science-fiction'],
            ['nomCategorie' => 'Roman', 'informationCategorie' => 'Livres romans'],
            ['nomCategorie' => 'Fantasy', 'informationCategorie' => 'Livres de fantasy'],
            ['nomCategorie' => 'Bio', 'informationCategorie' => 'Livres de biologie'],
            // Ajoutez d'autres catégories selon vos besoins
        ];

        foreach ($categoriesData as $categoryData) {
            $categorie = new Categorie();
            $categorie
                ->setNomCategorie($categoryData['nomCategorie'])
                ->setInformationCategorie($categoryData['informationCategorie']);
            $manager->persist($categorie);

            // Créer des livres
            for ($k = 0; $k < 3; $k++) {
                $livre = new Livre();
                $randomCategory = $categoriesData[array_rand($categoriesData)];
                $livre
                    ->setTitreLivre('Titre du livre ' . ($k + 1))
                    ->setFichierLivre('test.txt')
                    ->setAuteurLivre($users[3]) // 4ème utilisateur créé
                    ->setCategorieLivre($categorie)
                    ->setResumeLivre('Résumé du livre ' . ($k + 1))
                    ->setPrixLivre(random_int(10, 100))
                    ->setDateUploadLivre(new \DateTime());
                $manager->persist($livre);
            }
        }

        $manager->flush();
    }

    // Fonction utilitaire pour obtenir un élément aléatoire d'un tableau
    private function getRandomItem(array $array)
    {
        if (empty($array)) {
            return null; // Ou vous pouvez retourner une valeur par défaut appropriée
        }

        return $array[array_rand($array)];
    }
}
