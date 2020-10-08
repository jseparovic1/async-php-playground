<?php

$serverSocket = stream_socket_server(
    'tcp://127.0.0.1:5050',
    $errno,
    $errstr
);

if (!$serverSocket) {
    echo "$errstr ($errno)<br />\n";
    exit(0);
}

echo "Listening on http://".stream_socket_get_name($serverSocket, false) . "\n";

while ($conn = stream_socket_accept($serverSocket)) {
    echo "Accepted connection\n";
    fwrite($conn, "HTTP/1.1 200 OK\r\n");
    fwrite($conn, "Content-Type: text/html\r\n");
    fwrite($conn, "Content-Length: 11\r\n\r\n");
    fwrite($conn, "Hello world");
    fclose($conn);
}
