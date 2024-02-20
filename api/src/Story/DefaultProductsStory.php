<?php

namespace App\Story;

use App\Factory\ProductFactory;
use Zenstruck\Foundry\Story;

final class DefaultProductsStory extends Story
{
    public function build(): void
    {
        ProductFactory::createMany(100);
    }
}
