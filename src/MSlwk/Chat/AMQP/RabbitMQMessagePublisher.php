<?php
/**
 * File: RabbitMQMessagePublisher.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\AMQP;

use MSlwk\Chat\Api\AMQP\AMQPPublisherInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RabbitMQMessagePublisher
 * @package MSlwk\Chat\AMQP
 */
final class RabbitMQMessagePublisher implements AMQPPublisherInterface
{
    /**
     * @var AMQPChannel
     */
    private $channel;

    /**
     * @var string
     */
    private $exchange;

    /**
     * RabbitMQMessagePublisher constructor.
     */
    public function __construct()
    {
        $this->exchange = getenv(self::RABBITMQ_EXCHANGE_ENV);
        $queue = getenv(self::RABBITMQ_QUEUE_ENV);
        $connection = new AMQPStreamConnection(
            getenv(self::RABBITMQ_HOSTNAME_ENV),
            getenv(self::RABBITMQ_PORT_ENV),
            getenv(self::RABBITMQ_USERNAME_ENV),
            getenv(self::RABBITMQ_PASSWORD_ENV),
            getenv(self::RABBITMQ_VHOST_ENV)
        );
        $this->channel = $connection->channel();
        $this->channel = $connection->channel();
        $this->channel->queue_declare($queue, false, true, false, false);
        $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $this->channel->queue_bind($queue, $this->exchange);
    }

    /**
     * @param string $message
     */
    public function publishMessage(string $message): void
    {
        $message = new AMQPMessage(
            $message,
            [
                'content_type' => 'text/plain',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $this->channel->basic_publish($message, $this->exchange);
    }
}
