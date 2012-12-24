<?php
namespace Foo;

use Symfony\Component\HttpFoundation\Request;

class DefaultController
{
    use \Nov\Controller\Helper;

    public function __construct(Request $request)
    {
        $this->surname = $request->get('surname', 'Ayuso');
    }

    public function html()
    {
        return "Hi " . $this->getFromRequest('name') . ' ' . $this->surname;
    }

    public function hello()
    {
        return $this->viewRender('hello.twig', array('name' => $this->surname));
    }
}
