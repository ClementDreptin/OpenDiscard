<?php
namespace OpenDiscard\api\socket;

use Ratchet\ConnectionInterface;

class Room {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function join(ConnectionInterface $client) {
        if (!$this->clients->contains($client)) {
            $this->clients->attach($client);
        }
    }

    public function leave(ConnectionInterface $client) {
        if ($this->clients->contains($client)) {
            $this->clients->detach($client);
        }
    }

    public function broadcast(ConnectionInterface $sender, $message) {
        foreach ($this->clients as $receiver) {
            if ($sender !== $receiver) {
                $receiver->send($message);
            }
        }
    }
}