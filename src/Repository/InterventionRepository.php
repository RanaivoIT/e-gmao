<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intervention>
 *
 * @method Intervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervention[]    findAll()
 * @method Intervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    public function add(Intervention $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Intervention $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySite($site)
    {
        return $this->createQueryBuilder('i')
                    ->orderBy('i.id', 'DESC')
                    ->join('i.equipement', 'e')
                    ->where('e.site = :site')
                    ->setParameter('site', $site)
                    ->getQuery()
                    ->getResult();
    }

    public function findByTech($tech)
    {
        return $this->createQueryBuilder('i')
                    ->orderBy('i.id', 'DESC')
                    ->join('i.techniciens', 't')
                    ->where('t = :tech')
                    ->setParameter('tech', $tech)
                    ->getQuery()
                    ->getResult();
    }
    public function findByTechAndState($tech, $state)
    {
        return $this->createQueryBuilder('i')
                    ->orderBy('i.id', 'DESC')
                    ->join('i.techniciens', 't')
                    ->where('t = :tech and i.state = :state')
                    ->setParameter('tech', $tech)
                    ->setParameter('state', $state)
                    ->getQuery()
                    ->getResult();
    }
    public function findBySiteAndState($site, $state)
    {
        return $this->createQueryBuilder('i')
                    ->orderBy('i.id', 'DESC')
                    ->join('i.equipement', 'e')
                    ->where('e.site = :site and i.state = :state')
                    ->setParameter('site', $site)
                    ->setParameter('state', $state)
                    ->getQuery()
                    ->getResult();
    }
    public function findAll()
    {
        return $this->findBy([], ['id' => 'DESC']);
    }
//    /**
//     * @return Intervention[] Returns an array of Intervention objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'DESC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Intervention
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
