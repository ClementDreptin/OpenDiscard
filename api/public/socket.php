<?php
require '../src/vendor/autoload.php';

use OpenDiscard\api\socket\Socket;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\SecureServer;

$app = new HttpServer(
    new WsServer(
        new Socket()
    )
);

$loop = Factory::create();

$secure_websockets = new Server('0.0.0.0:3000', $loop);
$secure_websockets = new SecureServer($secure_websockets, $loop, [
    'local_cert' => parse_ini_file('../src/config/config.ini')['cert_path'],
    'local_pk' => parse_ini_file('../src/config/config.ini')['privkey_path'],
    'verify_peer' => false
]);

$secure_websockets_server = new IoServer($app, $secure_websockets, $loop);
$secure_websockets_server->run();