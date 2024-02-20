<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findProducts( int $itemPerPage, int $page = 1):array
    {
        $firstResult = ($page -1) * $itemPerPage;

        $queryBuilder = $this->createQueryBuilder('e');
        return $queryBuilder
            ->getQuery()
            ->setFirstResult($firstResult)
            ->setMaxResults($itemPerPage)
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countItems(): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id) AS total')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findOne(array $criteria, array $orderBy = null): Product |null
    {
        return $this->findOneBy($criteria, $orderBy);
    }
}
