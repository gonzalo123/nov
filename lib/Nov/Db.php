<?php

/*
 * This file is part of the Nov package.
 *
 * (c) Gonzalo Ayuso <gonzalo123@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nov;

use Nov\Db\Iface;

class Db implements Iface
{
    private $dsn;
    private $username;
    private $password;

    public function setDsn($dsn)
    {
        $this->dsn = $dsn;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /** @return \PDO */
    public function getPDO()
    {
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        return new \PDO($this->dsn, $this->username, $this->password, $options);
    }
}