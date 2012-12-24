<?php
include __DIR__ . "/fixtures/Examples/Redirect.php";
include __DIR__ . "/fixtures/Examples/Redirect2.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class InstancerRedirectTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleRedirect()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'hi'));
        $instance->setContainer($this->getContainerForUrl('/?name=gonzalo'));
        $this->assertEquals("Ho", $instance->invoke());
    }

    public function testDoubleRedirect()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'double'));
        $instance->setContainer($this->getContainerForUrl('/?name=gonzalo'));
        $this->assertEquals("Ho", $instance->invoke());
    }

    public function testRedirecttoAnotherController()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'hey'));
        $instance->setContainer($this->getContainerForUrl('/?name=gonzalo'));
        $this->assertEquals("Let's go", $instance->invoke());
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
        $parser->expects($this->any())->method('getRequest')->will($this->returnValue(Request::create('/')));

        return $parser;
    }

    private function getContainerForUrl($url)
    {
        $container = new ContainerBuilder();
        $loader    = new YamlFileLoader($container, new FileLocator(__DIR__ . '/fixtures/config/'));
        $loader->load('services.yml');
        $container->set('request', Request::create($url));

        return $container;
    }
}