<?php
include __DIR__ . "/fixtures/Examples/Methods.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DependencyInjectionInMethodsTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleRedirect()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Methods', 'hi', '/?name=Gonzalo'));
        $this->assertEquals("Hi Gonzalo", $instance->invoke());
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
}