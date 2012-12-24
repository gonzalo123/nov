<?php
namespace Examples;

use Symfony\Component\DependencyInjection\Container;

class Db
{
    use \Nov\Controller\Helper;

    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getFromContainer('db1')->getPDO();
    }

    public function exec($string)
    {
        return $this->pdo->exec($string);
    }

    public function select($string)
    {
        return $this->pdo->query($string)->fetchAll();
    }
}
