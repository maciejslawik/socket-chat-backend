<?php
/**
 * File: RabbitMQMessagePublisher.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\AMQP;

use MSlwk\Chat\Api\AMQPPublisherInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RabbitMQMessagePublisher
 * @package MSlwk\Chat\AMQP
 */
final class RabbitMQMessagePublisher implements AMQPPublisherInterface
{
    const HOSTNAME_ENV = 'RABBITMQ_HOST';
    const PORT_ENV = 'RABBITMQ_MAIN_PORT';
    const USERNAME_ENV = 'RABBITMQ_USER';
    const PASSWORD_ENV = 'RABBITMQ_PASS';
    const QUEUE_ENV = 'RABBITMQ_QUEUE';
    const EXCHANGE_ENV = 'RABBITMQ_EXCHANGE';
    const VHOST_ENV = 'RABBITMQ_VHOST';

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
        $this->exchange = getenv(self::EXCHANGE_ENV);
        $queue = getenv(self::QUEUE_ENV);
        $connection = new AMQPStreamConnection(
            getenv(self::HOSTNAME_ENV),
            getenv(self::PORT_ENV),
            getenv(self::USERNAME_ENV),
            getenv(self::PASSWORD_ENV),
            getenv(self::VHOST_ENV)
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
