<?php
/**
 * File: AbstractRabbitMQSubscriber.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\AMQP;

use MSlwk\Chat\Api\AMQP\AMQPInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AbstractRabbitMQSubscriber
 * @package MSlwk\Chat\AMQP
 */
abstract class AbstractRabbitMQSubscriber implements AMQPInterface
{
    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var string
     */
    protected $exchange;

    /**
     * AbstractRabbitMQSubscriber constructor.
     */
    public function __construct()
    {
        $this->initSubscriber();
    }

    /**
     * @return void
     */
    public function start(): void
    {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * @param AMQPMessage $message
     * @return void
     */
    abstract public function onMessage(AMQPMessage $message): void;

    /**
     * @param AMQPChannel $channel
     * @param AMQPStreamConnection $connection
     * @return void
     */
    public function shutdown(AMQPChannel $channel, AMQPStreamConnection $connection): void
    {
        $channel->close();
        $connection->close();
    }

    /**
     * @return string
     */
    abstract protected function getConsumerTag(): string;

    /**
     * @return string
     */
    abstract protected function getQueueName(): string;

    /**
     * @return void
     */
    private function initSubscriber(): void
    {
        $this->exchange = getenv(self::RABBITMQ_EXCHANGE_ENV);
        $queue = $this->getQueueName();
        $connection = new AMQPStreamConnection(
            getenv(self::RABBITMQ_HOSTNAME_ENV),
            getenv(self::RABBITMQ_PORT_ENV),
            getenv(self::RABBITMQ_USERNAME_ENV),
            getenv(self::RABBITMQ_PASSWORD_ENV),
            getenv(self::RABBITMQ_VHOST_ENV)
        );
        $this->channel = $connection->channel();
        $this->channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false
        );
        $this->channel->queue_bind($queue, $this->exchange);
        $this->channel->basic_consume(
            $queue,
            $this->getConsumerTag(),
            false,
            false,
            false,
            false,
            [$this, 'onMessage']
        );

        register_shutdown_function([$this, 'shutdown'], $this->channel, $connection);
    }
}
