<?php
include __DIR__ . "/../../vendor/autoload.php";
error_reporting(-1);

use Nov\Framework;

$framework = new Framework(__DIR__ . '/..');
$response = $framework->getResponse();
$response->send();