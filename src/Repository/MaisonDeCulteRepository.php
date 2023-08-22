<?php

namespace App\Repository;

use App\Entity\MaisonDeCulte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaisonDeCulte>
 *
 * @method MaisonDeCulte|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaisonDeCulte|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaisonDeCulte[]    findAll()
 * @method MaisonDeCulte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaisonDeCulteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaisonDeCulte::class);
    }

//    /**
//     * @return MaisonDeCulte[] Returns an array of MaisonDeCulte objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MaisonDeCulte
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
