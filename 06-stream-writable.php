<?php

require __DIR__ .'/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$readable = new \React\Stream\ReadableResourceStream(STDIN, $loop);
$writable = new \React\Stream\WritableResourceStream(STDOUT, $loop);
$toUpper = new \React\Stream\ThroughStream(function ($chunk) {
    return strtoupper($chunk);
});

//$readable->on('data', function ($chunk) use ($writable) {
//    $writable->write("Got: ". $chunk);
//});

// Same as above
$readable
    ->pipe($toUpper)
    ->pipe($writable);

$readable->on('end', function () {
    echo "Finished". PHP_EOL;
});

$loop->run();
