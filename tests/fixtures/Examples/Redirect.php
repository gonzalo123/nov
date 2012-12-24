<?php
namespace Examples;

class Redirect
{
    use \Nov\Controller\Helper;

    public function hi()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setAction('ho');

        return $redirect;
    }

    public function hey()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setController('Redirect2');
        $redirect->setAction('go');

        return $redirect;
    }

    public function ho()
    {
        return "Ho";
    }


    public function double()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setAction('double2');

        return $redirect;
    }

    public function double2()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setAction('ho');

        return $redirect;
    }

    public function redirectWithRequestParams()
    {
        $redirect = $this->getFromContainer('redirect');
        $redirect->setAction('redirectWithRequestParams2');

        return $redirect;
    }

    public function redirectWithRequestParams2()
    {
        return "Hi " . $this->getFromRequest('name');
    }
}
