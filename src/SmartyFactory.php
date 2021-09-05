<?php

namespace Hitsi\Mezzio\SmartyRenderer;

use ArrayObject;
use Psr\Container\ContainerInterface;
use Smarty;
use SmartyException;


class SmartyFactory
{

    public function __invoke(ContainerInterface $container) : Smarty
    {
        $config = $container->has('config') ? $container->get('config') : [];

        if (! is_array($config) && ! $config instanceof ArrayObject) {
            throw new Exception\InvalidConfigException(sprintf(
                '"config" service must be an array or ArrayObject for the %s to be able to consume it; received %s',
                self::class,
                is_object($config) ? get_class($config) : gettype($config)
            ));
        }

        $config = SmartyRendererFactory::mergeConfig($config);

        // Create the engine instance
        $smarty = new Smarty();
        if (!isset($config['template_dir']) || empty($config['template_dir'])) {
            throw new Exception\InvalidConfigException('"paths" configuration must be a exist');
        } else {
            $smarty->setTemplateDir($config['template_dir']);
        }
        if (!isset($config['compile_dir']) || empty($config['compile_dir'])) {
            throw new Exception\InvalidConfigException('"compile_dir" configuration must be a exist');
        } else {
            $smarty->setCompileDir($config['compile_dir']);
        }
        if (!isset($config['config_dir']) || empty($config['config_dir'])) {
            throw new Exception\InvalidConfigException('"config_dir" configuration must be a exist');
        } else {
            $smarty->setConfigDir($config['config_dir']);
        }
        if (!isset($config['cache_dir']) || empty($config['cache_dir'])) {
            throw new Exception\InvalidConfigException('"cache_dir" configuration must be a exist');
        } else {
            $smarty->setCacheDir($config['cache_dir']);
        }

        $smarty->compile_check = $config['compile_check'] ?? true;
        $smarty->force_compile = $config['force_compile'] ?? false;
        $smarty->caching = $config['caching'] ?? Smarty::CACHING_LIFETIME_CURRENT;
        $smarty->debugging = (bool) ($config['debugging'] ?? false);

        // Add template paths // Для smarty используется addTemplateDir
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $path) {
            is_numeric($namespace) ?: $smarty->addTemplateDir($path, $namespace);
        }

        //add Plugins
        $plugins = isset($config['plugins']) && is_array($config['plugins'])
            ? $config['plugins']
            : [];
        $this->injectPlugins($smarty, $container, $plugins);

        //add global params
        $params = isset($config['global']) && is_array($config['global'])
            ? $config['global']
            : [];
        $smarty->assign($params);

        return $smarty;
    }

    public function injectPlugins(Smarty $smarty, ContainerInterface $container, array $plugins):void
    {
        foreach ($plugins as $name => $plugin) {
            if ($function=$container->get($plugin)) {
                try {
                    $smarty->registerPlugin('function', $name, $function);
                } catch (SmartyException $e) {
                    trigger_error($e);
                }
            }
        }
    }

}
