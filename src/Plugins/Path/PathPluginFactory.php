<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Mezzio\Router\RouterInterface;
use Hitsi\Mezzio\SmartyRenderer\Exception\InvalidConfigException;
use Psr\Container\ContainerInterface;

class PathPluginFactory
{

    public function __invoke(ContainerInterface $container): PathPlugin
    {
        $router = $container->has(RouterInterface::class)
            ? RouterInterface::class
            : null;
        if ($router === null) {
            throw new InvalidConfigException(sprintf('Missing required `%s` dependency.', RouterInterface::class));
        }

        return new PathPlugin($container->get($router));
    }
}