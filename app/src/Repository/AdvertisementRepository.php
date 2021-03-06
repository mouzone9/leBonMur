<?php

namespace App\Repository;

use App\Entity\Advertisement;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Advertisement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advertisement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advertisement[]    findAll()
 * @method Advertisement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertisementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advertisement::class);
    }

    public function findAllByStatus($status)
    {

        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :val')
            ->setParameter('val', $status)
            ->orderBy('a.publicationDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllByUser(User $user) {
        return $this->createQueryBuilder('a')
            ->andWhere('a.seller = :val')
            ->setParameter('val', $user->getId())
            ->orderBy('a.publicationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByQuery (string $query) {
        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :query')
            ->orWhere('a.description LIKE :query')
            ->andWhere("a.status LIKE 'PUBLIC'")
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('a.publicationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Advertisement[] Returns an array of Advertisement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advertisement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
