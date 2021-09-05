<?php

namespace Hitsi\Mezzio\SmartyRenderer\Plugins;

interface PluginsInterface
{
    public function __invoke($params);
}