<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 *
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    public function add(Demande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Demande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findBySite($site)
    {
        return $this->createQueryBuilder('d')
                    ->orderBy('d.id', 'DESC')
                    ->join('d.equipement', 'e')
                    ->where('e.site = :site')
                    ->setParameter('site', $site)
                    ->getQuery()
                    ->getResult();
    }
    public function findBySiteAndState($site, $state)
    {
        return $this->createQueryBuilder('d')
                    ->orderBy('d.id', 'DESC')
                    ->join('d.equipement', 'e')
                    ->where('e.site = :site and d.state = :state')
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
//     * @return Demande[] Returns an array of Demande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'DESC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Demande
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
