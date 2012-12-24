<?php

use Nov\Responser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ResponserTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpeResponse()
    {
        $responser = new Responser($this->getInstanceMock());

        $response = $responser->getResponse();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals('Hi', $response->getContent());
    }

    public function testGetRequestFromResponse()
    {
        $responser = new Responser($this->getInstanceMock());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $responser->getRequest());
    }

    public function testJsonResponse()
    {
        /** @var Nov\Instance $instance */
        $instance = $this->getMockBuilder('Nov\Instance')
                ->disableOriginalConstructor()
                ->getMock();
        $instance->expects($this->any())->method('invoke')->will($this->returnValue(array(1, 2, 3)));
        $responser = new Responser($instance);

        $this->assertEquals(json_encode(array(1, 2, 3), true), $responser->getResponse()->getContent());
    }

    /**
     * @return Nov\Instance
     */
    private function getInstanceMock()
    {
        $instance = $this->getMockBuilder('Nov\Instance')
                ->disableOriginalConstructor()
                ->getMock();
        $instance->expects($this->any())->method('invoke')->will($this->returnValue("Hi"));
        $instance->expects($this->any())->method('getRequest')->will($this->returnValue(Request::create('/')));

        return $instance;
    }
}