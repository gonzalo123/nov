<?php
namespace Examples;
use \Symfony\Component\HttpFoundation\Request;

class Methods
{
    use \Nov\Controller\Helper;

    public function hi($name)
    {
        return "Hi {$name}";
    }

    public function ho(Request $request)
    {
        return "Ho " . $request->get('name');
    }
}