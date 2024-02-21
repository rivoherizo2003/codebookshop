<?php

namespace App\Action;

use App\Entity\DataWeather;
use App\Entity\Place;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Contracts\HttpClient\HttpClientInterface;
#[AsController]
final class GetWeatherAction
{
    public function __construct(private HttpClientInterface $client, private ParameterBagInterface $containerBag)
    {
    }

    public function __invoke(Place $place): DataWeather
    {
        $response = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather',
            [
                'query' => [
                    'lat' => $place->getLatitude(),
                    'lon' => $place->getLongitude(),
                    'appid' => $this->containerBag->get('open_weather')
                ]
            ]
        );

        $content = json_decode($response->getContent());
        $weather = new DataWeather();
        $weather->setCoord($content->coord);
        $weather->setWeather($content->weather);


        return new DataWeather();
    }
}
