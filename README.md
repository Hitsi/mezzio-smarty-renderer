# Smarty Integration for Mezzio

Provides [Smarty](https://www.smarty.net/) integration for
[Mezzio](https://docs.laminas.dev//mezzio/).

## Installation

Install this library using composer:

```bash
$ composer require hitsi/mezzio-smarty-renderer
```
Mezzio team recommend using a dependency injection container, and typehint against
[container-interop](https://github.com/container-interop/container-interop). We
can recommend the following implementations:

- [laminas-servicemanager](https://github.com/laminas/laminas-servicemanager):
  `composer require laminas/laminas-servicemanager`
- [pimple-interop](https://github.com/moufmouf/pimple-interop):
  `composer require mouf/pimple-interop`
- [Aura.Di](https://github.com/auraphp/Aura.Di): `composer require aura/di`

## Configuration

You need to include the `Hitsi\Mezzio\SmartyRenderer\ConfigProvider` in your
[`config/config.php`](https://github.com/mezzio/mezzio-skeleton/blob/master/config/config.php).
Optional configuration can be stored in `config/autoload/templates.global.php`.

```php
'templates' => [
    'extension' => 'file extension used by templates; defaults to tpl',
    'paths' => [
        // namespace / path pairs
    ],
],
'smarty' => [
    'template_dir' => 'default/main templates',
    'cache_dir' => 'path to cached templates',
    'compile_dir' => 'path to compiled templates',
    'config_dir' => 'path to config',
    'assets_url' => 'base URL for assets',
    'assets_version' => 'base version for assets',
    'plugins' => [
        // your own plugins
    ],
    'globals' => [
        // Global variables passed to smarty templates
    ],
    'compile_check' => true, // https://www.smarty.net/docs/en/variable.compile.check.tpl
    'force_compile' => false, // https://www.smarty.net/docs/en/variable.force.compile.tpl
    'caching' => false, // https://www.smarty.net/docs/en/variable.caching.tpl
    'debugging' => false, // https://www.smarty.net/docs/en/variable.debugging.tpl
],
```


## Smarty Plugin

The included Smarty plugin adds support for url generation. 

- ``path``: Render the relative path for a given route and parameters. If there
  is no route, it returns the current path.

  ```smarty
  {#path route='article_show' id='3'}
  Generates: /article/3
  ```
  
- ``url``: Render the absolute url for a given route and parameters. If there is
  no route, it returns the current url.

  ```smarty
  {url route='article_show' id='3'}
  Generates: http://example.com/article/3
  ```

- ``absolute_url``: Render the absolute url from a given path. If the path is
  empty, it returns the current url.

  ```smarty
  {absolute_url route='path/to/something'}
  Generates: http://example.com/path/to/something
  ```

- ``asset`` Render an (optionally versioned) asset url.

  ```smarty
  {asset route='path/to/asset/name.ext' version='3'}
  Generates: path/to/asset/name.ext?v=3
  ```

  To get the absolute url for an asset:

  ```smarty
  {absolute_url route={asset route='path/to/asset/name.ext' version='3'}}
  Generates: http://example.com/path/to/asset/name.ext?v=3
  ```
  
## Configuration Plugin

You need to include the `Hitsi\Mezzio\SmartyRenderer\Plugins\ConfigProvider` in your
[`config/config.php`](https://github.com/mezzio/mezzio-skeleton/blob/master/config/config.php).
Optional you can add you own plugin like this.

```php
'factories' => [
    Path\To\YourPlugin::class => Path\To\YourPluginFactory::class,
],
'smarty' => [
    'plugins' => [
        'myplugin' => Path\To\YourPlugin::class,
    ],
],
```

Then use them

```smarty
{myplugin param1="test" param2="done"}
```

