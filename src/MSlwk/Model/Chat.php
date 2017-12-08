<?php
/**
 * File: Chat.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Model;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

/**
 * Class Chat
 * @package MSlwk\Model
 */
class Chat implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     * Chat constructor.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = $this->generateMessage($msg);
        foreach ($this->clients as $client) {
            $client->send(json_encode([$message]));
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * @param string $payload
     * @return Message
     */
    private function generateMessage(string $payload): Message
    {
        $messageData = json_decode($payload);
        $message = new Message();
        $message->setMessage($messageData->message);
        $message->setNickname($messageData->nickname);
        return $message;
    }
}
