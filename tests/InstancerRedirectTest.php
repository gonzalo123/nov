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
        $url      = '/?name=gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'hi', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $this->assertEquals("Ho", $instance->invoke());
    }

    public function testDoubleRedirect()
    {
        $url      = '/?name=gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'double', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $this->assertEquals("Ho", $instance->invoke());
    }

    public function testRedirecttoAnotherController()
    {
        $url      = '/?name=gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'hey', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $this->assertEquals("Let's go", $instance->invoke());
    }

    public function testRedirectWithRequestParams()
    {
        $url      = '/?name=gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Redirect', 'redirectWithRequestParams', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $this->assertEquals("Hi gonzalo", $instance->invoke());
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

    private function getContainerForUrl($url)
    {
        $container = new ContainerBuilder();
        $loader    = new YamlFileLoader($container, new FileLocator(__DIR__ . '/fixtures/config/'));
        $loader->load('services.yml');
        $container->set('request', Request::create($url));

        return $container;
    }
}