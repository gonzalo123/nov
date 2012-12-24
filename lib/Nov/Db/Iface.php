<?php

/*
 * This file is part of the Nov package.
 *
 * (c) Gonzalo Ayuso <gonzalo123@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nov\Db;

interface Iface
{
    public function setDsn($dsn);

    public function setPassword($password);

    public function setUsername($username);

    /** @return \PDO */
    public function getPDO();
}