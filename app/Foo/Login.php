<?php
namespace Foo;

use Symfony\Component\HttpFoundation\Request;
use Nov\Parser;
use Nov\Instance;

class Login
{
    use \Nov\Controller\Helper;

    public function __construct()
    {

    }

    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    public function hi()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setController('DefaultController');
        $redirect->setAction('html');

        return $redirect;
    }

    public function ho()
    {
        return "Ho";
    }

    public function html()
    {
        $username = $this->getFromRequest('username');
        $password = $this->getFromRequest('password');

        if ($username == 'aaa') {
            $redirect = $this->getFromContainer('redirect');
            $redirect->setAction('hi');

            return $redirect;


            /*
                    $container = $this->getContainer();

                    $request = Request::create("/");

                    $parser = new Parser($request);
                    $parser->setRootNamespace($container->getParameter('root_namespace'));
                    $parser->setDefaultAction($container->getParameter('default_action'));
                    $parser->setDefaultController($container->getParameter('default_controller'));
                    $parser->parse();

                    $instance = new Instance($parser);
                    $instance->setContainer($this->getContainer());

                    echo $instance->invoke();
                    die();
            */
        }

        return $this->viewRender('login.html.twig', array(
            'title' => 'Login Title'
        ));
    }
}