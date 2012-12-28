<?php
namespace Examples;

use Symfony\Component\DependencyInjection\Container;

class Db
{
    use \Nov\Controller\Helper;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $conn;

    public function __construct()
    {
        $this->conn = $this->getFromContainer('db1');
    }

    public function exec($string)
    {
        return $this->conn->exec($string);
    }

    public function select($string)
    {
        return $this->conn->query($string)->fetchAll();
    }
}
