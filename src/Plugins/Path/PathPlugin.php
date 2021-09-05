<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Hitsi\Mezzio\SmartyRenderer\Plugins\PluginsInterface;
use Mezzio\Router\RouterInterface;

class PathPlugin implements PluginsInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function __invoke($params): string
    {
        if (!isset($params['route'])) {
            trigger_error("path: missing 'route' parameter");
        }
        $name = $params['route'];
        array_unshift($params, $params['route']);
        unset($params['route']);
        return $this->router->generateUri($name, $params);
    }
}