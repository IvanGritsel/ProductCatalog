<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByPage(int $page = 1, array $filters = []): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->where('1 = 1'); // Needed to make filters work, easy way
        foreach ($filters as $k => $v) {
            $this->setFilter($qb, $k, $v);
        }
        if (!isset($filters['order'])) {
            $qb->orderBy('p.id', 'ASC');
        }
        $query = $qb->getQuery();
        $paginator = new Paginator($query, true);
        $paginator->getQuery()->setFirstResult(($page - 1) * 10)->setMaxResults(10);
        return $paginator;
    }

    private function setFilter(QueryBuilder $query, string $case, string $condition): void
    {
        switch ($case) {
            case 'order': {
                $c = explode('_', $condition);
                $query->orderBy("p.$c[0]", $c[1]);
                break;
            }
            case 'price': {
                $c = explode('_', $condition);
                $query->andWhere("p.priceByn $c[0] $c[1]");
                break;
            }
            case 'release': {
                $c = explode('_', $condition);
                $query->andWhere("p.releaseDate $c[0] '$c[1]'");
                break;
            }
            case 'manufacturer': {
                $query->andWhere("p.manufacturer LIKE '%$condition%'");
                break;
            }
            case 'type': {
                $query->andWhere("p.productType = $condition");
                break;
            }
        }
    }
}
