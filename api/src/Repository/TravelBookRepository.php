<?php

namespace App\Repository;

use App\Entity\TravelBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TravelBook>
 *
 * @method TravelBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelBook[]    findAll()
 * @method TravelBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelBookRepository extends ServiceEntityRepository implements TravelBookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelBook::class);
    }

    function paginateResults(int $itemPerPage, int $page = 1)
    {
        // TODO: Implement paginateResults() method.
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    function countItems(): int
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id) AS total')
            ->getQuery()
            ->getSingleScalarResult();
    }

    function save(TravelBook $travelBook)
    {
        $this->getEntityManager()->flush();
    }

    function add(TravelBook $travelBook)
    {
        $this->getEntityManager()->persist($travelBook);
        $this->getEntityManager()->flush();
    }

    function delete(TravelBook $travelBook)
    {
        $this->getEntityManager()->remove($travelBook);
    }

    function findOne(array $criteria)
    {
        return $this->findOneBy($criteria);
    }
}
