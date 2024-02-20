<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Paths;
use ApiPlatform\OpenApi\OpenApi;

final class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct( private OpenApiFactoryInterface $decorated)
    {
    }
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $paths = $openApi->getPaths()->getPaths();

        $filteredPaths = new Paths();

        foreach ($paths as $path => $pathItem) {
            // If a prefix is configured on API Platform's routes, it must appear here.
            if ($path === '/weathers') {
                continue;
            }
            $filteredPaths->addPath($path, $pathItem);
        }
        return $openApi->withPaths($filteredPaths);
    }
}
