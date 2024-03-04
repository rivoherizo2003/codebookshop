<?php

namespace App\Repository;

interface RepositoryInterface
{
    function paginateResults(int $itemPerPage, int $page = 1);

    function countItems(): int;

    function findOne(array $criteria);
}
