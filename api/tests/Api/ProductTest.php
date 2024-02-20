<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ProductFactory;

class ProductTest extends AbstractTest
{
    public function testGetProductsByPage(): void
    {
        ProductFactory::createMany(40);
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', '/api/products?page=1');

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            "@context"         => "/contexts/Product",
            "@id"              => "/api/products",
            "@type"            => "hydra:Collection",
            "hydra:totalItems" => 40,
            "hydra:view"       => [
                "@id"         => "/api/products?page=1",
                "@type"       => "hydra:PartialCollectionView",
                "hydra:first" => "/api/products?page=1",
                "hydra:last"  => "/api/products?page=2",
                "hydra:next"  => "/api/products?page=2",
            ]
        ]);


        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', '/api/products?page=2');

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            "@context"         => "/contexts/Product",
            "@id"              => "/api/products",
            "@type"            => "hydra:Collection",
            "hydra:totalItems" => 40,
            "hydra:view"       => [
                "@id"            => "/api/products?page=2",
                "@type"          => "hydra:PartialCollectionView",
                "hydra:first"    => "/api/products?page=1",
                "hydra:last"     => "/api/products?page=2",
                'hydra:previous' => '/api/products?page=1',
            ]
        ]);
    }

    public function testGetProduct()
    {
        $product = ProductFactory::createOne([
            'name' => 'TESTPRODUCT',
            'description' => 'desc test',
            'price' => 265.32
        ]);

        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', '/api/products?id='.$product->getId());

        $this->assertResponseIsSuccessful();
    }
}
