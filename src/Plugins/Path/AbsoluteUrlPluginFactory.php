<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Mezzio\Helper\ServerUrlHelper;
use Hitsi\Mezzio\SmartyRenderer\Exception\InvalidConfigException;
use Psr\Container\ContainerInterface;

class AbsoluteUrlPluginFactory
{
    public function __invoke(ContainerInterface $container): AbsoluteUrlPlugin
    {
        $serverUrlHelper = $container->has(ServerUrlHelper::class)
            ? ServerUrlHelper::class
            : null;
        if ($serverUrlHelper === null) {
            throw new InvalidConfigException(sprintf('Missing required `%s` dependency.', ServerUrlHelper::class));
        }

        return new AbsoluteUrlPlugin($container->get($serverUrlHelper));
    }
}