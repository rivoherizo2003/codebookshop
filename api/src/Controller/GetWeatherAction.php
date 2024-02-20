<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\DataWeather;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

#[AsController]
final class GetWeatherAction
{
    public function __construct(private HttpClientInterface $client, private ParameterBagInterface $containerBag)
    {
    }

    public function __invoke(Place $place)
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


        return $weather;
    }
}
