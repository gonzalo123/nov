<?php
include __DIR__ . "/fixtures/Examples/Index3.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class InstancerWithTraitsViewIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testActionWithRequest()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Index3', 'action'));
        $instance->setContainer($this->getContainerForUrl('/'));
        $this->assertInstanceOf('\\Examples\\Index3', $instance->getInstance());
        $this->assertEquals("Hi gonzalo", $instance->invoke());
    }

    public function getContainerForUrl($url)
    {
        $container = new ContainerBuilder();
        $loader    = new YamlFileLoader($container, new FileLocator(__DIR__ . '/fixtures/config/'));
        $loader->load('services.yml');
        $container->set('request', Request::create($url));
        $container->setParameter('root_dir', __DIR__ . '/fixtures');

        return $container;
    }

    /**
     * @param $namespace
     * @param $controller
     * @param $action
     * @return Nov\Parser
     */
    private function getParserMockFor($namespace, $controller, $action)
    {
        $classFullName = "\\{$namespace}\\{$controller}";
        $parser = $this->getMockBuilder('Nov\Parser')->disableOriginalConstructor()->getMock();
        $parser->expects($this->any())->method('getAction')->will($this->returnValue($action));
        $parser->expects($this->any())->method('getController')->will($this->returnValue($controller));
        $parser->expects($this->any())->method('getNamespace')->will($this->returnValue($namespace));
        $parser->expects($this->any())->method('getClassFullName')->will($this->returnValue($classFullName));

        return $parser;
    }
}
