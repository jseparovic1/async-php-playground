<?php

$request = "GET / HTTP/1.1\r\nHost: example.com\r\n\r\n";
$connection = fsockopen('www.example.com', 80);

fwrite($connection, $request);

while (!feof($connection)) {
    echo fgets($connection);
}

fclose($connection);
