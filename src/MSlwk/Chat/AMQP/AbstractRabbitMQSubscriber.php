<?php
/**
 * File: AbstractRabbitMQSubscriber.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\AMQP;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AbstractRabbitMQSubscriber
 * @package MSlwk\Chat\AMQP
 */
abstract class AbstractRabbitMQSubscriber
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
     * @return void
     */
    private function initSubscriber(): void
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
        $this->channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false
        );
        $this->channel->exchange_declare(
            $this->exchange,
            'direct',
            false,
            true,
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
