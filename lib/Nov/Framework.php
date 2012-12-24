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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Response;

class Framework
{
    private $rootDir;
    private $response;
    private $request;
    private $container;

    public function __construct($rootDir, $request = null)
    {
        $this->rootDir   = $rootDir;
        $this->request   = $request;

        $this->container = $this->getContainer();
        $this->response  = $this->container->get('responser')->getResponse();
    }

    private function getContainer()
    {
        $container = new ContainerBuilder();
        $loader    = new YamlFileLoader($container, new FileLocator($this->rootDir . '/config'));
        $loader->load('services.yml');
        $container->setParameter('root_dir', $this->rootDir);
        if (!is_null($this->request)) {
            $container->set('request', $this->request);
        }
        $container->set('container', $container);

        return $container;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}