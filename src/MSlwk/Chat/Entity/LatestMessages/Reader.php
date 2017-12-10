<?php
/**
 * File: Reader.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Entity\LatestMessages;

use MSlwk\Chat\Api\LatestMessages\LatestMessagesReaderInterface;
use MSlwk\Chat\Entity\Message;

/**
 * Class Reader
 * @package MSlwk\Chat\Entity\LatestMessages
 */
final class Reader implements LatestMessagesReaderInterface
{
    /**
     * @var \Redis
     */
    private $connection;

    /**
     * Reader constructor.
     */
    public function __construct()
    {
        $this->connection = new \Redis();
        $this->connection->connect(getenv(self::REDIS_HOST_ENV), getenv(self::REDIS_PORT_ENV));
        $this->connection->auth(getenv(self::REDIS_PASSWORD_ENV));
    }

    /**
     * @return array
     */
    public function getLatestMessages(): array
    {
        $storedArray = $this->connection->lrange(self::REDIS_KEY, 0, self::MESSAGES_NUMBER);
        $messageObjectArray = [];

        foreach ($storedArray as $messageJson) {
            $messageObjectArray[] = $this->generateMessageObject($messageJson);
        }

        return $messageObjectArray;
    }

    /**
     * @param string $messageJson
     * @return Message
     */
    private function generateMessageObject(string $messageJson): Message
    {
        $messageData = json_decode($messageJson);
        $message = new Message();
        $message->setMessage($messageData->message);
        $message->setNickname($messageData->nickname);
        return $message;
    }
}
