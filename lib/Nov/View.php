<?php

/*
 * This file is part of the Nov package.
 *
 * (c) Gonzalo Ayuso <gonzalo123@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nov;

class View
{
    private $namespaces = array();
    private $cachePath;

    public function __construct($cachePath, $cacheAutoReload)
    {
        $this->cachePath       = $cachePath;
        $this->cacheAutoReload = $cacheAutoReload;
    }

    public function registerNamespace($namespace, $path)
    {
        $this->namespaces[$namespace] = $path;
    }

    public function getTwig($class)
    {
        $arr              = explode('\\', $class);
        $currentNamespace = $arr[0];
        foreach ($this->namespaces as $namespace => $basePath) {
            if ($currentNamespace == $namespace) {
                $tplPath = dirname($basePath . '/' . $this->buildClassPath($class));
                return $this->getTwigEnvironment($tplPath);
            }
        }
        throw new \Exception("Namespace not found", 404);
    }

    private function buildClassPath($class)
    {
        return str_replace('\\', '/', $class);
    }

    private function getTwigEnvironment($tplPath)
    {
        $loader = new \Twig_Loader_Filesystem($tplPath);
        $twig   = new \Twig_Environment($loader, array(
            'cache'       => $this->cachePath,
            'auto_reload' => $this->cacheAutoReload
        ));
        return $twig;
    }

    public function render($tpl, $arguments = array(), $class = null)
    {
        if (is_null($class)) {
            $class = get_called_class();
        }
        return $this->getTwig($class)->render($tpl, $arguments);
    }
}