<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getAllClientsQuery()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery();
    }


    public function getAllClientsArray()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id', 'c.email', 's.result')
            ->innerJoin(Score::class, 's', 'with', 's.client = c.id')
            ->orderBy('c.id')
            ->getQuery()
            ->getArrayResult();
    }

    public function getClientAsArray($clientId)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id', 'c.email', 's.result')
            ->innerJoin(Score::class, 's', 'with', 's.client = c.id')
            ->andWhere('c.id = :val')
            ->setParameter('val', $clientId)
            ->orderBy('c.id')
            ->getQuery()
            ->getArrayResult();
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
