<?php

namespace Nov;

use Nov\Parser;
use Nov\Controller\Redirect;

class Instance
{
    private $parser;
    private $container;
    private $rClass;

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
        $this->rClass = new \ReflectionClass($className);

        $hasConstruct = $this->rClass->hasMethod('__construct');
        if ($hasConstruct) {
            $params = $this->getMethodParams($this->rClass->getMethod('__construct'));
        } else {
            $params = array();
        }
        if ($this->rClassNeedsToBeInejectedWithContainer($this->rClass)) {
            $class = $this->getClassWithInjectedConatainer($hasConstruct, $params);
        } else {
            $class = $this->rClass->newInstanceArgs($params);
        }
        return $class;
    }

    private function getClassWithInjectedConatainer($hasConstruct, $params)
    {
        $class = $this->rClass->newInstanceWithoutConstructor();

        $rProperty = $this->rClass->getProperty('_container');
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
        $params = $this->getRequestParametersForMethod($action);
        $out    = call_user_func_array(array($instance, $action), $params);

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

    public function getRequestParametersForMethod($action)
    {
        $params = array();
        $rMethod = $this->rClass->getMethod($action);
        $request = $this->parser->getRequest();

        foreach ($rMethod->getParameters() as $param) {
            $paramenterName = $param->getName();
            $requestParameterValue = $request->get($paramenterName);

            if (!is_null($requestParameterValue)) {
                $params[] = $requestParameterValue;
            }
        }

        return $params;
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