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

use \Symfony\Component\HttpFoundation\Request;

class Parser
{
    private $request;
    private $rootNamespace;
    private $defaultController;
    private $defaultAction;

    private $namespace;
    private $controller;
    private $action;
    private $classFullName;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->decodedPath = pathinfo($this->request->getPathInfo());
    }

    public function setRootNamespace($rootNamespace)
    {
        $this->rootNamespace = $rootNamespace;
    }

    public function setDefaultController($defaultController)
    {
        $this->defaultController = $defaultController;
    }

    public function setDefaultAction($defaultAction)
    {
        $this->defaultAction = $defaultAction;
    }

    private function getNamespaceFromPathInfo()
    {
        $namespace = '\\' .  str_replace(' ', '\\', trim(ucwords(str_replace('/', ' ', $this->decodedPath['dirname']))));
        return $namespace == '\\' ? $this->rootNamespace : $this->rootNamespace . $namespace;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getClassFullName()
    {
        return $this->classFullName;
    }

    public function parse()
    {
        $this->namespace     = $this->getNamespaceFromPathInfo();
        $this->controller    = $this->getControllerFromPathInfo();
        $this->action        = $this->getActionFromPathInfo();
        $this->classFullName = $this->namespace . '\\' . $this->controller;
    }

    private function getControllerFromPathInfo()
    {
        $controller = ucwords($this->decodedPath['filename']);
        if ($controller == '') {
            $controller = $this->defaultController;
        }

        return $controller;
    }

    private function getActionFromPathInfo()
    {
        $action = isset($this->decodedPath['extension']) ? $this->decodedPath['extension'] : null;
        if ($action == '') {
            $action = $this->defaultAction;
        }

        return $action;
    }
}