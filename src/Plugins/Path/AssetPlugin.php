<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

class AssetPlugin
{
    private ?string $assetsUrl;
    private ?string $assetsVersion;

    public function __construct(?string $assetsUrl, ?string $assetsVersion)
    {
        $this->assetsUrl = $assetsUrl;
        $this->assetsVersion = $assetsVersion;
    }

    public function __invoke($params): string
    {
        if (!isset($params['route'])) {
            trigger_error("path: missing 'route' parameter");
        }
        $route = $params['route'];

        $assetsVersion = isset($params['version']) && $params['version'] !== '' ? $params['version'] : $this->assetsVersion;

        // One more time, in case $this->assetsVersion was null or an empty string
        $assetsVersion = $assetsVersion !== null && $assetsVersion !== '' ? '?v=' . $assetsVersion : '';

        return $this->assetsUrl . $route . $assetsVersion;
    }
}