<?php
namespace Examples;

class Methods
{
    use \Nov\Controller\Helper;

    public function hi($name)
    {
        return "Hi {$name}";
    }
}
