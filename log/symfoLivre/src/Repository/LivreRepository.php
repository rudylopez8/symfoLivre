<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function findAllOrderByTitre()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.titreLivre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Fonction de tri par prix de livre
    public function findAllOrderByPrix()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.prixLivre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Fonction de tri par date d'upload de livre
    public function findAllOrderByDateUpload()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.dateUploadLivre', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findAllOrderByAuteur()
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.auteurLivre', 'auteur')
            ->orderBy('auteur.nomUser', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Fonction de tri par catégorie de livre
    public function findAllOrderByCategorie()
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.categorieLivre', 'categorie')
            ->orderBy('categorie.nomCategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Fonction de tri par prix de livre (en descendant)
    public function findAllOrderByPrixDesc()
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.prixLivre', 'DESC')
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
