<?php

use Nov\Framework;
use Symfony\Component\HttpFoundation\Request;

include __DIR__ . '/fixtures/Examples/DefaultController.php';

class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    public function testFrameworkTest()
    {
        $framework = new Framework(__DIR__ . '/fixtures', Request::create("/"));
        $this->assertEquals("hi", $framework->getResponse()->getContent());
    }
}