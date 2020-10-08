<?php

use React\Socket\ConnectionInterface;

include __DIR__ . '/vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

const IP = '127.0.0.1:6000';

$server = new \React\Socket\Server(IP, $loop);

echo 'Socket server running on ' . IP . PHP_EOL;

$pool = new ConnectionPool();

$server->on('connection', function (ConnectionInterface $connection) use ($pool) {
    $pool->join($connection);
});

$loop->run();

class ConnectionPool
{
    private SplObjectStorage $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function join(\React\Socket\ConnectionInterface $new): void
    {
        $new->write("Welcome to ReactPHP chat.\n");
        $new->write("Enter your name:");
        $this->setConnectionName($new, '');

        $this->initializeConnection($new);
    }

    private function sendAll(string $message, ConnectionInterface $except): void
    {
        foreach ($this->connections as $connection) {
            if ($connection !== $except || $this->getConnectionName($connection) === '') {
                $connection->write("$message");
            }
        }
    }

    private function setConnectionName(ConnectionInterface $connection, string $name): void
    {
        $this->connections->offsetSet($connection, $name);
    }

    private function getConnectionName(ConnectionInterface $connection): ?string
    {
        return $this->connections->offsetGet($connection);
    }

    protected function addNewUser($data, ConnectionInterface $new): void
    {
        $name = trim(str_replace(["\n", "\r"], '', $data));
        $this->setConnectionName($new, $name);
        $this->sendAll("User {$name} joined the chat.\n", $new);
    }

    private function initializeConnection(ConnectionInterface $new): void
    {
        $new->on('data', function ($data) use ($new) {
            $name = $this->getConnectionName($new);

            if ($name === null || $name === '') {
                $this->addNewUser($data, $new);
                return;
            }

            $this->sendAll("{$name}: $data", $new);
        });

        $new->on('close', function () use ($new) {
            $name = $this->getConnectionName($new);
            $this->connections->offsetUnset($new);

            $this->sendAll("User {$name} left the chat.\n", $new);
        });
    }
}
