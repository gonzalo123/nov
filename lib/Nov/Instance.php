<?php

namespace Nov;

use Nov\Parser;
use Nov\Controller\Redirect;

class Instance
{
    private $parser;
    private $container;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getInstance()
    {
        return $this->getNewInstanceOfClass($this->parser->getClassFullName());
    }

    private function getNewInstanceOfClass($className)
    {
        $rClass       = new \ReflectionClass($className);
        $hasConstruct = $rClass->hasMethod('__construct');
        if ($hasConstruct) {
            $params = $this->getMethodParams($rClass->getMethod('__construct'));
        } else {
            $params = array();
        }
        if ($this->rClassNeedsToBeInejectedWithContainer($rClass)) {
            $class = $this->getClassWithInjectedConatainer($rClass, $hasConstruct, $params);
        } else {
            $class = $rClass->newInstanceArgs($params);
        }
        return $class;
    }

    private function getClassWithInjectedConatainer(\ReflectionClass $rClass, $hasConstruct, $params)
    {
        $class = $rClass->newInstanceWithoutConstructor();

        $rProperty = $rClass->getProperty('_container');
        $rProperty->setAccessible(true);
        $rProperty->setValue($class, $this->container);

        if ($hasConstruct) {
            call_user_func_array(array($class, '__construct'), $params);
        }

        return $class;
    }

    private function rClassNeedsToBeInejectedWithContainer($rClass)
    {
        return in_array('Nov\Controller\Helper', $rClass->getTraitNames());
    }

    public function invoke()
    {
        return $this->invokeAction($this->getInstance(), $this->parser->getAction());
    }

    private function invokeAction($instance, $action)
    {
        $out = call_user_func(array($instance, $action));

        if ($out instanceof Redirect) {

            $redirectNamespace  = $out->getNamespace();
            $redirectController = $out->getController();

            $namespace  = $redirectNamespace == '' ? $this->parser->getNamespace() : $redirectNamespace;
            $controller = $redirectController == '' ? $this->parser->getController() : $redirectController;

            $obj = $this->getNewInstanceOfClass($namespace . '\\' . $controller);
            $out = $this->invokeAction($obj, $out->getAction());
        }

        return $out;
    }

    public function getRequest()
    {
        return $this->parser->getRequest();
    }

    private function getMethodParams($rMethod)
    {
        $params = array();
        foreach ($rMethod->getParameters() as $param) {
            $params = $this->injectDependencies($params, $param, $rMethod);
        }

        return $params;
    }

    private function injectDependencies($params, $param, $rMethod)
    {
        $parameterName = $param->getName();
        if (isset($param->getClass()->name)) {
            switch ($param->getClass()->name) {
                case 'Symfony\Component\DependencyInjection\Container':
                    $params[$parameterName] = $this->container;
                    break;
                case 'Symfony\Component\HttpFoundation\Request':
                    $params[$parameterName] = $this->container->get('request');
                    break;
            }
        }

        return $params;
    }
}