<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Hitsi\Mezzio\SmartyRenderer\SmartyRendererFactory;
use Psr\Container\ContainerInterface;

class AssetPluginFactory
{

    public function __invoke(ContainerInterface $container):AssetPlugin
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $config = SmartyRendererFactory::mergeConfig($config);

        return new AssetPlugin(
            $config['assets_url'] ?? '',
            $config['assets_version'] ?? ''
        );
    }
}