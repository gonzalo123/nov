<?php
namespace Nov\Controller;

trait Helper
{
    protected $_container;

    protected function getFromContainer($key)
    {
        return $this->_container->get($key);
    }

    protected function getContainer()
    {
        return $this->_container;
    }

    /** @return Monolog\Logger */
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

    /**
     * @return use Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->_container->get('request');
    }

    protected function getFromRequest($key, $default = null, $deep = false)
    {
        return $this->getRequest()->get($key, $default, $deep);
    }
}
