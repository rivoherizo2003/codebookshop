<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use App\Factory\ProductFactory;
use Symfony\Component\HttpFoundation\Response;

class ProductTest extends AbstractTest
{
    public function testGetProductsByPage(): void
    {
        ProductFactory::createMany(40, [
            'name' => 'TESTPRODUCT',
            'description' => 'desc test',
            'price' => 265.32
        ]);
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
        $this->assertArraySubset([
            "@id"             => "/api/products/1",
            "@type"           => "Product",
            'name' => 'TESTPRODUCT',
            'desc' => 'desc test',
            'unitPrice' => 265.32
        ], $response->toArray()["hydra:member"][0]);


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

    public function testCreateProduct()
    {
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('POST', '/api/products', [
            'json' => [
                'name' => 'new product',
                'desc' => "new product desc",
                'unitPrice' => 124.25
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testPatchEndpointProduct()
    {
        $product = ProductFactory::createOne([
            'name' => 'TESTPRODUCT',
            'description' => 'desc test',
            'price' => 265.32
        ]);

        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN'],
        ])->request('PATCH', '/api/products/'.$product->getId(), [
            'json' => [
                'desc' => 'patched desc'
            ],
            'headers' => [
                'content-type' => 'application/merge-patch+json'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $product = ProductFactory::find(['id' => $product->getId()]);
        $this->assertEquals('patched desc', $product->getDescription());
        $this->assertEquals(265.32, $product->getPrice());
    }

    public function testDeleteProduct()
    {
        $product = ProductFactory::createOne([
            'name' => 'TESTPRODUCT',
            'description' => 'desc test',
            'price' => 265.32
        ]);

        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN'],
        ])->request('DELETE', '/api/products/'.$product->getId());

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
