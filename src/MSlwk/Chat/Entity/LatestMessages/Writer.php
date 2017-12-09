<?php
/**
 * File: Writer.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Entity\LatestMessages;

use MSlwk\Chat\AMQP\AbstractRabbitMQSubscriber;
use MSlwk\Chat\Api\LatestMessages\LatestMessagesInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Writer
 * @package MSlwk\Chat\Entity\LatestMessages
 */
class Writer extends AbstractRabbitMQSubscriber implements LatestMessagesInterface
{
    const CONSUMER_TAG = 'redis_latest_storage';

    /**
     * @var \Redis
     */
    private $connection;

    /**
     * Writer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->connection = new \Redis();
        $this->connection->connect(getenv(self::REDIS_HOST_ENV), getenv(self::REDIS_PORT_ENV));
        $this->connection->auth(getenv(self::REDIS_PASSWORD_ENV));
    }

    /**
     * @param AMQPMessage $message
     * @return void
     */
    public function onMessage(AMQPMessage $message): void
    {
        $this->connection->rpush(self::REDIS_KEY, $message->body);

        $count = count($this->connection->lRange(self::REDIS_KEY, 0, -1));
        if ($count > self::MESSAGES_NUMBER) {
            $this->connection->lpop(self::REDIS_KEY);
        }
    }

    /**
     * @return string
     */
    protected function getConsumerTag(): string
    {
        return self::CONSUMER_TAG;
    }
}
