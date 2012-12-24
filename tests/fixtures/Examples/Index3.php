<?php
namespace Examples;

class Index3
{
    use \Nov\Controller\Helper;

    public function action()
    {
        return $this->viewRender('hello.twig', array('name' => 'gonzalo'));
    }
}
