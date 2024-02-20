<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Factory\UserFactory;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class AbstractTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    private ?string $token = null;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createUserAndClientWithCredentials(array $userCredentials, $token = null): Client
    {
        if(!array_key_exists('email', $userCredentials) || !array_key_exists('password', $userCredentials)) throw new BadCredentialsException();

        if(null === $this->token){
            UserFactory::createOne($userCredentials);
            $this->token = $this->getToken([
                'email'    => $userCredentials['email'],
                'password' => $userCredentials['password']
            ]);
        }

        return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $this->token]]);
    }

    private function getToken(array $body): string
    {
        if ($this->token) {
            return $this->token;
        }

        $response = static::createClient()->request('POST', '/auth', ['json' => $body]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();
        $this->token = $data['token'];

        return $data['token'];
    }
}
