<?php

include __DIR__. '/../vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$server = stream_socket_server('tcp://127.0.0.1:5000');
stream_set_blocking($server, false);

echo "Webserver starting at http://127.0.0.1:5000\n\n";

$loop->addReadStream($server, function ($server) use ($loop) {
    $connection = stream_socket_accept($server);

    echo "Got connection...\n";

    $data = '';
    $data .= "HTTP/1.1 200 OK\r\n";
    $data .= "Content-Type: text/html\r\n";
    $data .= "Content-Length: 11\r\n\r\n";
    $data .= "Hello world";

    $loop->addWriteStream($connection, function ($connection) use (&$data, $loop) {
        $bytesWritten = fwrite($connection, $data);

        if ($bytesWritten === strlen($data)) {
            // All god we wrote everything...
            fclose($connection);
            $loop->removeReadStream($connection);
        } else {
            $data = substr($data, 0, strlen($bytesWritten));
        }
    });
});

$loop->addPeriodicTimer(5, function () {
    $memory = memory_get_usage() / 1024;
    $formatted = number_format($memory, 3). 'K';

    echo "Memory usage ${formatted}\n";
});

$loop->run();

