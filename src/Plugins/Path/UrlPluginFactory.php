<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Router\RouterInterface;
use Hitsi\Mezzio\SmartyRenderer\Exception\InvalidConfigException;
use Psr\Container\ContainerInterface;

class UrlPluginFactory
{
    public function __invoke(ContainerInterface $container): UrlPlugin
    {
        $serverUrlHelper = $container->has(ServerUrlHelper::class)
            ? ServerUrlHelper::class
            : null;
        if ($serverUrlHelper === null) {
            throw new InvalidConfigException(sprintf('Missing required `%s` dependency.', ServerUrlHelper::class));
        }
        $router = $container->has(RouterInterface::class)
            ? RouterInterface::class
            : null;
        if ($router === null) {
            throw new InvalidConfigException(sprintf('Missing required `%s` dependency.', RouterInterface::class));
        }

        return new UrlPlugin(
            $container->get($router),
            $container->get($serverUrlHelper)
        );
    }
}