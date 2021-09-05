<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins\Path;

use Hitsi\Mezzio\SmartyRenderer\Plugins\PluginsInterface;
use Mezzio\Helper\ServerUrlHelper;

class AbsoluteUrlPlugin implements PluginsInterface
{
    private ServerUrlHelper $serverUrlHelper;

    public function __construct(ServerUrlHelper $serverUrlHelper)
    {
        $this->serverUrlHelper = $serverUrlHelper;
    }

    public function __invoke($params): string
    {
        if (!isset($params['route'])) {
            trigger_error("path: missing 'route' parameter");
        }
        $path = $params['route'];
        array_unshift($params, $params['route']);
        unset($params['route']);
        return $this->serverUrlHelper->generate($path);
    }
}