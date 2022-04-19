<?php

namespace App\Repository;

use App\Entity\Controls;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Controls|null find($id, $lockMode = null, $lockVersion = null)
 * @method Controls|null findOneBy(array $criteria, array $orderBy = null)
 * @method Controls[]    findAll()
 * @method Controls[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControlsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Controls::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Controls $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Controls $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Controls[] Returns an array of Controls objects
      */

    public function findDocumentAFournir($value)
    {
        return $this->createQueryBuilder('c')
            ->select('c','d')
            ->join('c.documents','d')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Controls
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
