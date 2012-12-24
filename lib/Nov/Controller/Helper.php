<?php
namespace Nov\Controller;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nov\Controller\Redirect;
use Monolog\Logger;

trait Helper
{
    protected $_container;

    protected function getFromContainer($key)
    {
        return $this->_container->get($key);
    }

    /** @return ContainerBuilder */
    protected function getContainer()
    {
        return $this->_container;
    }

    /** @return Logger */
    protected function getLogger()
    {
        return $this->_container->get('logger');
    }

    protected function viewRender($tpl, $arguments = array(), $class = null)
    {
        if (is_null($class)) {
            $class = get_called_class();
        }
        return $this->_container->get('view')->getTwig($class)->render($tpl, $arguments);
    }

    /** @return Symfony\Component\HttpFoundation\Request */
    protected function getRequest()
    {
        return $this->_container->get('request');
    }

    protected function getFromRequest($key, $default = null, $deep = false)
    {
        return $this->getRequest()->get($key, $default, $deep);
    }

    /** @return Redirect */
    protected function getRedirect()
    {
        return $this->_container->get('redirect');
    }
}
