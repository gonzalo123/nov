<?php
include_once __DIR__ . "/fixtures/Examples/Db.php";

use Nov\Instance;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DatabaseInjectionTest extends \PHPUnit_Framework_TestCase
{
    public function testDbInjection()
    {
        $url = '/?name=Gonzalo';
        $instance = new Instance($this->getParserMockFor('Examples', 'Db', 'action', $url));
        $instance->setContainer($this->getContainerForUrl($url));
        $obj = $instance->getInstance();
        $this->assertEquals(0, $obj->exec("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY, title TEXT, message TEXT)"));
        $this->assertEquals(1, $obj->exec("INSERT INTO messages(id, title, message) VALUES (1, 'title', 'message')"));
        $data = $obj->select("SELECT * FROM messages");
        $this->assertEquals('title', $data[0]['title']);
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
        $container->set('container', $container);
        return $container;
    }
}