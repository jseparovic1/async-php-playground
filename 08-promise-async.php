<?php

require __DIR__ . '/vendor/autoload.php';

function http(string $url): \React\Promise\PromiseInterface
{
    $response = 'data';
//    $response = null;

    $deffered = new \React\Promise\Deferred();

    if ($response) {
        $deffered->resolve($response);
    } else {
        $deffered->reject(new Exception('error'));
    }

    return $deffered->promise();
}


http("http://google.com")
    ->then(
        function ($response) {
            echo "Good {$response}" . PHP_EOL;
        },
        function (Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }
    );

