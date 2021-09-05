<?php

namespace Hitsi\Mezzio\SmartyRenderer;

use Exception;
use Mezzio\Template\TemplateRendererInterface;
use Smarty;
use SmartyException;


class SmartyRenderer implements TemplateRendererInterface
{

    private Smarty $template;
    private array $defaultParams = [];
    private string $extension;

    public function __construct(?Smarty $template = null, string $extension = 'tpl')
    {

        if (null === $template) {
            $template = $this->createTemplate();
        }
        $this->template = $template;
        $this->extension = $extension;
    }

    /**
     * Create a default Twig environment
     */
    private function createTemplate(): SmartyFactory
    {
        return new SmartyFactory();
    }

    /**
     * @throws SmartyException
     */
    public function render(string $name, $params = []): string
    {
        foreach ($params as $key => $value) {
            $this->template->assign($key, $value);
        }
        foreach ($this->defaultParams as $key => $value) {
            $this->template->assign($key, $value);
        }
        return $this->template->fetch($this->normalizeTemplate($name));
    }

    /**
     * Add a path for template
     *
     * @param string $path
     * @param string|null $namespace
     *
     */
    public function addPath(string $path, string $namespace = null): void
    {
        $this->template->addTemplateDir($path, $namespace);
    }

    /**
     * Get the template directories
     *
     * @return array
     */
    public function getPaths(): array
    {
        $templateDir = $this->template->getTemplateDir();
        if (is_array($templateDir)) {
            return $templateDir;
        }

        $paths = [];
        $paths[0] = $this->template->getTemplateDir();
        return $paths;
    }

    /**
     * Normalize namespaced template.
     *
     * Normalizes templates in the format "namespace::template" to
     * "[namespace]template".
     *
     * @param string $template
     *
     * @return string
     */
    public function normalizeTemplate(string $template): string
    {
        $template = preg_replace('#^([^:]+)::(.*)$#', '[$1]$2', $template);
        if (!preg_match('#\.[a-z]+$#i', $template)) {
            return sprintf('%s.%s', $template, $this->extension);
        }

        return $template;
    }

    /**
     * Add params to template
     *
     * @param string $templateName
     * @param string $param
     * @param mixed $value
     */
    public function addDefaultParam(string $templateName, string $param, $value): void
    {
        $this->defaultParams[$param] = $value;
    }

    /**
     * try call Smarty function
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        try {
            return $this->template->$name(...$arguments);
        } catch (Exception $e) {
            return trigger_error('Unknown smarty function: ' . $name);
        }
    }

}
