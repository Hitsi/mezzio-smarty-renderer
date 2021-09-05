<?php

namespace Hitsi\Mezzio\SmartyRenderer;

use ArrayObject;
use Hitsi\Mezzio\SmartyRenderer\Exception;
use Psr\Container\ContainerInterface;
use Smarty;

class SmartyRendererFactory
{

    public function __invoke(ContainerInterface $container): SmartyRenderer
    {
        $config      = $container->has('config') ? $container->get('config') : [];
        $config      = self::mergeConfig($config); //проброс конфига из контейнера в фабрику
        $environment = $this->getEnvironment($container);
        return new SmartyRenderer($environment, $config['extension'] ?? 'tpl');
    }


    public static function mergeConfig($config): array
    {
        $config = $config instanceof ArrayObject ? $config->getArrayCopy() : $config;

        if (! is_array($config)) {
            throw new Exception\InvalidConfigException(sprintf(
                'Config service MUST be an array or ArrayObject; received %s',
                is_object($config) ? get_class($config) : gettype($config)
            ));
        }

        $frameworkConfig = isset($config['templates']) && is_array($config['templates'])
            ? $config['templates']
            : [];
        $smartyConfig   = isset($config['smarty']) && is_array($config['smarty'])
            ? $config['smarty']
            : [];

        return array_replace_recursive($frameworkConfig, $smartyConfig);
    }

    private function getEnvironment(ContainerInterface $container): Smarty
    {
        if ($container->has(Smarty::class)) {
            return $container->get(Smarty::class);
        }

        trigger_error(sprintf(
            '%s now expects you to register the factory %s for the service %s; '
            . 'please update your dependency configuration.',
            self::class,
            SmartyFactory::class,
            Smarty::class
        ), E_USER_DEPRECATED);

        $factory = new SmartyFactory();
        return $factory($container);
    }
}
