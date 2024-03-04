<?php

namespace App\Tests\Api;

use Symfony\Component\HttpFoundation\Response;

class TravelBookTest extends AbstractTest
{
    public function testGenerateTravelBook()
    {
        $response = $this->createUserAndClientWithCredentials([
            'email'    => 'admin@codebookshop.com',
            'password' => 'secret',
            'roles'    => ['ROLE_ADMIN', 'ROLE_USER'],
        ])->request('POST', '/api/travel_books',  [
            'json' => [
                'title' => 'this is  test',
                'aboutTravel' => 'description test'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
    }
}
