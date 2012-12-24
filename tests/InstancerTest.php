<?php
include __DIR__ . "/fixtures/Examples/Index.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;

class InstancerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Index', 'action'));
        $this->assertInstanceOf('\\Examples\\Index', $instance->getInstance());
    }

    public function testInvokeAction()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Index', 'action'));
        $this->assertEquals("Hi", $instance->invoke());
    }

    public function testInvokeAction2()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Index', 'action2'));
        $this->assertEquals("Goodbye", $instance->invoke());
    }

    public function testGetRequest()
    {
        $instance = new Instance($this->getParserMockFor('Examples', 'Index', 'action2'));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $instance->getRequest());
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
}