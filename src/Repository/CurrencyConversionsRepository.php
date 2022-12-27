<?php

namespace App\Repository;

use App\Entity\CurrencyConversions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyConversions>
 *
 * @method CurrencyConversions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyConversions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyConversions[]    findAll()
 * @method CurrencyConversions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyConversionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyConversions::class);
    }

    public function save(CurrencyConversions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CurrencyConversions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByDate(string $date): ?CurrencyConversions
    {
        return $this->createQueryBuilder('c')
            ->andWhere("c.date = '$date'")
            ->getQuery()
            ->getOneOrNullResult();
    }
}
