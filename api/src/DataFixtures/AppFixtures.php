<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Story\DefaultBooksStory;
use App\Story\DefaultProductsStory;
use App\Story\DefaultReviewsStory;
use App\Story\DefaultUserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DefaultBooksStory::load();
        DefaultReviewsStory::load();
        DefaultUserStory::load();
        DefaultProductsStory::load();
    }
}
