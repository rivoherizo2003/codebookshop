<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Product;
use Doctrine\Common\Collections\Collection;

interface ProductRepositoryInterface
{
    /**
     * @param int $itemPerPage
     * @param int $page
     * @return array
     */
    function findProducts( int $itemPerPage, int $page = 1): array;

    function countItems(): int;

    function findOne(array $criteria, array $orderBy = null): Product | null;

    function save(Product $product, bool $isPersistNeeded = false);

    function delete(Product $product):void;
}
