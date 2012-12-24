<?php
namespace Examples;

class Index2
{
    use \Nov\Controller\Helper;

    private $name;

    public function __construct()
    {
        $this->name = $this->getFromRequest('name');
    }

    public function action()
    {
        return "Hi " . $this->getFromRequest('name');
    }

    public function action2()
    {
        return "Hi " . $this->getFromContainer('request')->get('name');
    }

    public function action3()
    {
        return "Hi " . $this->name;
    }
}
