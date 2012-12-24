<?php

use Symfony\Component\HttpFoundation\Request;
use Nov\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_CONTROLLER = 'DefaultController';
    const DEFAULT_ACTION     = 'defaultAction';
    const ROOT_NAMESPACE     = 'Nov';

    public function provider()
    {
        return array(
            array(
                'url'      => '/',
                'expected' => array(
                    'namespace'     => self::ROOT_NAMESPACE,
                    'controller'    => self::DEFAULT_CONTROLLER,
                    'action'        => self::DEFAULT_ACTION,
                    'fullClassName' => self::ROOT_NAMESPACE . '\\' . self::DEFAULT_CONTROLLER,
                )
            ),
            array(
                'url'      => '/index.html?a=1',
                'expected' => array(
                    'namespace'     => self::ROOT_NAMESPACE,
                    'controller'    => 'Index',
                    'action'        => 'html',
                    'fullClassName' => self::ROOT_NAMESPACE . '\\Index',
                )
            ),
            array(
                'url'      => '/foo/index.action',
                'expected' => array(
                    'namespace'     => self::ROOT_NAMESPACE . '\\Foo',
                    'controller'    => 'Index',
                    'action'        => 'action',
                    'fullClassName' => self::ROOT_NAMESPACE . '\\Foo\\Index',
                )
            )
        );
    }

    /** @dataProvider provider */
    public function testGetActionFromParsedRequest($url, $expected)
    {
        $parser = $this->getParserFromUrl($url);
        $this->assertEquals($expected['action'], $parser->getAction());
    }

    /** @dataProvider provider */
    public function testGetControllerFromParsedRequest($url, $expected)
    {
        $parser = $this->getParserFromUrl($url);
        $this->assertEquals($expected['controller'], $parser->getController());
    }

    /** @dataProvider provider */
    public function testGetNamespaceFromParsedRequest($url, $expected)
    {
        $parser = $this->getParserFromUrl($url);
        $this->assertEquals($expected['namespace'], $parser->getNamespace());
    }

    /** @dataProvider provider */
    public function testGetClassFullNameFromParsedRequest($url, $expected)
    {
        $parser = $this->getParserFromUrl($url);
        $this->assertEquals($expected['fullClassName'], $parser->getClassFullName());
    }

    /** @dataProvider provider */
    public function testGetRequest($url)
    {
        $parser = new Parser(Request::create($url));
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Request', $parser->getRequest());
    }

    private function getParserFromUrl($url)
    {
        $parser = new Parser(Request::create($url));
        $parser->setRootNamespace(self::ROOT_NAMESPACE);
        $parser->setDefaultController(self::DEFAULT_CONTROLLER);
        $parser->setDefaultAction(self::DEFAULT_ACTION);
        $parser->parse();

        return $parser;
    }
}