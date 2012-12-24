<?php
include __DIR__ . "/fixtures/Examples/Methods.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DependencyInjectionInMethodsTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleRequestInjection()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Methods', 'hi', '/?name=Gonzalo'));
        $this->assertEquals("Hi Gonzalo", $instance->invoke());
    }

    public function testDIInjection()
    {
        $url = '/?name=Gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Methods', 'ho', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $this->assertEquals("Ho Gonzalo", $instance->invoke());
    }

    /**
     * @param $namespace
     * @param $controller
     * @param $action
     * @param $url
     * @return Nov\Parser
     */
    private function getParserMockFor($namespace, $controller, $action, $url)
    {
        $classFullName = "\\{$namespace}\\{$controller}";

        $parser = $this->getMockBuilder('Nov\Parser')->disableOriginalConstructor()->getMock();
        $parser->expects($this->any())->method('getAction')->will($this->returnValue($action));
        $parser->expects($this->any())->method('getController')->will($this->returnValue($controller));
        $parser->expects($this->any())->method('getNamespace')->will($this->returnValue($namespace));
        $parser->expects($this->any())->method('getClassFullName')->will($this->returnValue($classFullName));
        $parser->expects($this->any())->method('getRequest')->will($this->returnValue(Request::create($url)));

        return $parser;
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
}