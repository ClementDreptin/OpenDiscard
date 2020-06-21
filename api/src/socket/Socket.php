<?php
namespace OpenDiscard\api\socket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface {
    public $rooms = [];

    public function onOpen(ConnectionInterface $conn) {}

    public function onMessage(ConnectionInterface $client, $dataString) {
        $data = json_decode($dataString);
        if ($data === null || !isset($data->action) || !isset($data->roomId)) return;
        if ($data->action === 'message' && !isset($data->message)) return;
        $action = $data->action;
        $roomId = $data->roomId;

        if (!isset($this->rooms[$roomId])) $this->rooms[$roomId] = new Room();

        switch($action) {
            case 'join':
                $this->rooms[$roomId]->join($client);
                break;
            case 'leave':
                $this->rooms[$roomId]->leave($client);
                break;
            case 'message':
                $message = json_encode($data->message);
                $this->rooms[$roomId]->broadcast($message);
                break;
            default:
                return;
        }
    }

    public function onClose(ConnectionInterface $conn) {}

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}