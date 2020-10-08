<?php

require __DIR__ .'/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$loop->addTimer(0.8, function () {
    echo "world\n";
});

$loop->addTimer(0.1, function () {
    echo "Hello ";
});

$timerTick = $loop->addPeriodicTimer(0.1, function () {
    echo 'tick!'. PHP_EOL;
});

$timerTock = $loop->addPeriodicTimer(0.1, function () {
    echo 'tok!'. PHP_EOL;
});

$loop->addTimer(2, function () use ($loop, $timerTick, $timerTock) {
    $loop->cancelTimer($timerTick);
    $loop->cancelTimer($timerTock);
    echo 'Canceling timers, done...'. PHP_EOL;
});

echo "I'm first out since I'm out of loop?";

$loop->addSignal(SIGINT, function (int $signal) {
    echo 'Caught user interrupt signal' . PHP_EOL. " " . $signal;
    exit();
});

$loop->run();
