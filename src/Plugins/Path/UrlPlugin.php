<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Hitsi\Mezzio\SmartyRenderer\Plugins\PluginsInterface;
use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Router\RouterInterface;

class UrlPlugin implements PluginsInterface
{
    private RouterInterface $router;
    private ServerUrlHelper $serverUrlHelper;

    public function __construct(RouterInterface $router, ServerUrlHelper $serverUrlHelper)
    {
        $this->router = $router;
        $this->serverUrlHelper = $serverUrlHelper;
    }

    public function __invoke($params): string
    {
        if (!isset($params['route'])) {
            trigger_error("path: missing 'route' parameter");
        }
        $route = $params['route'];
        array_unshift($params, $params['route']);
        unset($params['route']);
        return $this->serverUrlHelper->generate($this->router->generateUri($route, $params));
    }
}