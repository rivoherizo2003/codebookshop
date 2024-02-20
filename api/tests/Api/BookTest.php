<?php

namespace App\Tests\Api;

use App\Entity\Book;
use App\Factory\BookFactory;
use Symfony\Component\HttpFoundation\Response;

final class BookTest extends AbstractTest
{
    public function testGetCollection()
    {
        BookFactory::createMany(10);
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', '/api/books');

        $this->assertResponseIsSuccessful("successful getbooks collection");

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertCount(10, $response->toArray()['hydra:member']);
    }

    public function testCreateNewBook()
    {
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('POST', '/api/books', [
            'json' => [
                'isbn'            => '0099740915',
                'title'           => 'book test title',
                'description'     => 'book test description',
                'author'          => 'api test',
                'publicationDate' => '1985-07-31T00:00:00+00:00',
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context'        => '/contexts/Book',
            '@type'           => 'Book',
            'isbn'            => '0099740915',
            'title'           => 'book test title',
            'description'     => 'book test description',
            'author'          => 'api test',
            'publicationDate' => '1985-07-31T00:00:00+00:00',
        ]);
    }

    public function testCreateInvalidBook()
    {
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('POST', '/api/books', [
            'json' => [
                'isbn' => 'oifhd'
            ]
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');

        $this->assertJsonContains([
            '@type'             => 'ConstraintViolationList',
            'hydra:title'       => 'An error occurred',
            'hydra:description' => 'isbn: This value is neither a valid ISBN-10 nor a valid ISBN-13.
title: This value should not be blank.
description: This value should not be blank.
author: This value should not be blank.
publicationDate: This value should not be null.',
        ]);
    }

    public function testUpdateBook()
    {
        BookFactory::createOne([
            'isbn'            => '0099740915',
            'title'           => 'book test title',
            'description'     => 'book test description',
            'author'          => 'api test',
            'publicationDate' => \DateTimeImmutable::createFromMutable(new \DateTime()),
        ]);

        $client = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN'],
        ]);
        /**
         * findIriBy use static::getContainer
         */
        $iri = $this->findIriBy(Book::class, ['isbn' => '0099740915']);

        $client->request('PATCH', $iri, [
            'json'    => [
                'title' => 'book title tested'
            ],
            'headers' => ['content-type' => 'application/merge-patch+json']
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => 'book title tested',
            'isbn'  => '0099740915',
        ]);
    }

    public function testDeleteBook()
    {
        BookFactory::createOne([
            'isbn'            => '0099740915',
            'title'           => 'book test title',
            'description'     => 'book test description',
            'author'          => 'api test',
            'publicationDate' => \DateTimeImmutable::createFromMutable(new \DateTime()),
        ]);

        $client = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ]);
        $iri = $this->findIriBy(Book::class, ['isbn' => '0099740915']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $this->assertNull(static::getContainer()->get('doctrine')->getRepository(Book::class)->findOneby(['isbn' => '0099740915']));
    }

    public function testFilterBooksWithDatePublicationAfter()
    {
        BookFactory::createSequence([
                [
                    'isbn'            => '0099740915',
                    'title'           => 'book test title',
                    'description'     => 'book test description',
                    'author'          => 'api test',
                    'publicationDate' => \DateTimeImmutable::createFromMutable(new \DateTime('2024-01-01')),
                ], [
                    'isbn'            => '0099740915',
                    'title'           => 'book 2',
                    'description'     => 'book 2 description',
                    'author'          => 'api 2',
                    'publicationDate' => \DateTimeImmutable::createFromMutable(new \DateTime('2023-01-01')),
                ], [
                    'isbn'            => '0099740915',
                    'title'           => 'book 3',
                    'description'     => 'book 3',
                    'author'          => 'api 3',
                    'publicationDate' => \DateTimeImmutable::createFromMutable(new \DateTime('2023-12-01')),
                ]
            ]
        );

        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', '/api/books?publicationDate[after]=2024-01-01');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $response->toArray()['hydra:member']);

        $this->assertJsonContains([
            '@context'         => '/contexts/Book',
            '@id'              => '/api/books',
            '@type'            => 'hydra:Collection',
            'hydra:totalItems' => 1,
            "hydra:member"     => [
                [
                    "@id"             => "https://localhost/api/book/1",
                    "@type"           => "Book",
                    "id"              => 1,
                    "isbn"            => "0099740915",
                    "title"           => "book test title",
                    "description"     => "book test description",
                    "author"          => "api test",
                    "publicationDate" => "2024-01-01T00:00:00+00:00",
                ]
            ],
            "hydra:view"       => [
                "@id"   => "/api/books?publicationDate%5Bafter%5D=2024-01-01",
                "@type" => "hydra:PartialCollectionView",
            ]
        ]);
    }


}
