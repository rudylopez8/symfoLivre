<?php

namespace App\DataFixtures;

use App\Entity\Livre;
use App\Entity\User;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer un objet Faker pour générer des données aléatoires
        $faker = Faker\Factory::create('fr_FR');

        // Créer des utilisateurs
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user
                ->setNomUser($faker->lastName())
                ->setMailUser($faker->word())
                ->setPasswordUser($faker->word())
                ->setRoleUser($faker->word());
            $manager->persist($user);
        }

        // Créer des catégories
        for ($j = 0; $j < 5; $j++) {
            $categorie = new Categorie();
            $categorie
                ->setNomCategorie($faker->word())
                ->setInformationCategorie($faker->sentence());
            $manager->persist($categorie);

            // Créer des livres
            for ($k = 0; $k < 3; $k++) {
                $livre = new Livre();
                $livre
                    ->setTitreLivre($faker->sentence())
                    ->setFichierLivre('chemin/vers/le/fichier.pdf')
                    ->setAuteurLivre($user)
                    ->setCategorieLivre($categorie)
                    ->setResumeLivre($faker->word())
                    ->setPrixLivre($faker->randomFloat(2, 10, 100))
                    ->setDateUploadLivre($faker->dateTimeBetween('-1 year', 'now'));
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
