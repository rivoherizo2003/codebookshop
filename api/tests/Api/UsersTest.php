<?php

namespace App\Tests\Api;


use App\Entity\User;
use App\Factory\BookFactory;
use App\Factory\UserFactory;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

final class UsersTest extends AbstractTest
{
    public function testUnauthorizedAccessToGetUsers()
    {
        $response = UsersTest::createClient()->request("GET", "/api/users");

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetUsers()
    {
        UserFactory::createMany(9);
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request("GET", "/api/users");

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        /**
         * then with the user used to connect
         */
        $this->assertCount(10, $response->toArray()['hydra:member']);
    }

    public function testGetUser()
    {
        UserFactory::createOne([
            'email'    => 'test@codebookshop.com',
            'password' => 'secret',
        ]);
        $iri = $this->findIriBy(User::class, ['email' => 'test@codebookshop.com']);
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('GET', $iri);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJsonContains([
            'email' => 'test@codebookshop.com'
        ]);
    }
}
