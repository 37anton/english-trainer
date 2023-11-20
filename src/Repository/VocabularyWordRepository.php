<?php

namespace App\Repository;

use App\Entity\VocabularyWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VocabularyWord>
 *
 * @method VocabularyWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method VocabularyWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method VocabularyWord[]    findAll()
 * @method VocabularyWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocabularyWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VocabularyWord::class);
    }

//    /**
//     * @return VocabularyWord[] Returns an array of VocabularyWord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VocabularyWord
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
