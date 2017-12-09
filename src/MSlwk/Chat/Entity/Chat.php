<?php
/**
 * File: Chat.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Entity;

use MSlwk\Chat\Api\AMQPPublisherInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
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
     * @var AMQPPublisherInterface
     */
    protected $AMQPPublisher;

    /**
     * Chat constructor.
     * @param AMQPPublisherInterface $AMQPPublisher
     */
    public function __construct(AMQPPublisherInterface $AMQPPublisher)
    {
        $this->clients = new \SplObjectStorage;
        $this->AMQPPublisher = $AMQPPublisher;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

//        $conn->send(json_encode($msgs));
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = $this->generateMessageObject($msg);
        foreach ($this->clients as $client) {
            $client->send(json_encode([$message]));
            $this->publishMessage(json_encode($message));
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
    private function generateMessageObject(string $payload): Message
    {
        $messageData = json_decode($payload);
        $message = new Message();
        $message->setMessage($messageData->message);
        $message->setNickname($messageData->nickname);
        return $message;
    }

    /**
     * @param string $message
     */
    private function publishMessage(string $message): void
    {
        $this->AMQPPublisher->publishMessage($message);
    }
}
