<?php

namespace App\Repository;

use App\Entity\TravelBook;

interface TravelBookRepositoryInterface extends RepositoryInterface
{
    function save(TravelBook $travelBook);

    function add(TravelBook $travelBook);

    function delete(TravelBook $travelBook);
}
