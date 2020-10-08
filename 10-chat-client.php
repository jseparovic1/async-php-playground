<?php

use React\Socket\ConnectionInterface;

include __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

const IP = '127.0.0.1:6000';

$loop->run();
