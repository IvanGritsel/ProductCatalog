<?php

namespace App\Repository;

use App\Entity\ProductService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductService>
 *
 * @method ProductService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductService[]    findAll()
 * @method ProductService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductService::class);
    }

    public function save(ProductService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
