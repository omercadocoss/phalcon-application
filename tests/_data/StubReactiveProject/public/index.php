#!/usr/bin/env php
<?php

chdir(dirname(__DIR__));
include '../../../vendor/autoload.php';

$config = [
    'dispatcher' => ['controllerDefaultNamespace' => 'StubReactiveProject\Controller'],
    'routes'     => ['default' => ['pattern' => '/:controller/:action', 'paths' => ['controller' => 1, 'action' => 2]]],
];

$loop = \React\EventLoop\Factory::create();

$stream = function (\React\Http\Request $request, \React\Http\Response $response) use ($loop, $config) {
    $parser = \React\Http\StreamingBodyParser\Factory::create($request);
    \React\Http\StreamingBodyParser\BufferedSink::createPromise($parser)->then(
        function ($data) use ($request, $response, $loop, $config) {

            \Phapp\Application\Bootstrap::init($config)->runApplicationOn([
                'reactive' => [
                    'loop'     => $loop,
                    'message'  => new \Phapp\Application\Adapter\React\MultipartMessage($request, $data),
                    'response' => new \Phapp\Application\Adapter\React\Response($response),
                ],
            ]);
        }
    );
};

$socket = new \React\Socket\Server($loop);
$socket->on('connection', function (\React\Socket\Connection $client) {
    $_SERVER['REMOTE_ADDR'] = $client->getRemoteAddress();
});

$http = new \React\Http\Server($socket);
$http->on('request', $stream);
$socket->listen(80, '0.0.0.0');

$loop->run();
