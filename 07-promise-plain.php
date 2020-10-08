<?php

function http($url, callable $resolve, callable $reject) {
    $response = 'data';
    $response = null;

    if ($response) {
        $resolve($response);
    } else {
        $reject(new Exception('error'));
    }
}

http(
    "http://google.com",
    function ($response) {
        echo "Good {$response}". PHP_EOL;
    },
    function (Exception $exception) {
        echo $exception->getMessage(). PHP_EOL;
    }
);
