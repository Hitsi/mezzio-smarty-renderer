<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'smarty' => $this->getSmarty(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                Path\PathPlugin::class => Path\PathPluginFactory::class,
                Path\AbsoluteUrlPlugin::class => Path\AbsoluteUrlPluginFactory::class,
                Path\UrlPlugin::class => Path\UrlPluginFactory::class,
                Path\AssetPlugin::class => Path\AssetPluginFactory::class,
            ],
        ];
    }

    public function getSmarty(): array
    {
        return [
            'plugins' => [
                'path' => Path\PathPlugin::class,
                'absolute_url' => Path\AbsoluteUrlPlugin::class,
                'url' => Path\UrlPlugin::class,
                'asset' => Path\AssetPlugin::class,
            ]
        ];
    }
}
