<?php

namespace App\Repository;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Entity\Product;
use Doctrine\Common\Collections\Collection;

interface ProductRepositoryInterface extends RepositoryInterface
{

    function save(Product $product, bool $isPersistNeeded = false);

    function delete(Product $product):void;
}
