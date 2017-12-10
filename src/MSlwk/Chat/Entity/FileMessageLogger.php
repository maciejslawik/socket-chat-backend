<?php
/**
 * File: FileMessageLogger.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Entity;

use MSlwk\Chat\AMQP\AbstractRabbitMQSubscriber;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class FileMessageLogger
 * @package MSlwk\Chat\Entity
 */
final class FileMessageLogger extends AbstractRabbitMQSubscriber
{
    const CONSUMER_TAG = 'file_logger';
    const QUEUE_NAME = 'file_logger';
    const LOGGER_NAME = 'message_logger';
    const LOG_FILE = __DIR__ . '/../../../../var/log/messages.log';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * FileMessageLogger constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger(self::LOGGER_NAME);
        $this->logger->pushHandler(new StreamHandler(self::LOG_FILE, Logger::DEBUG));
    }

    /**
     * @param AMQPMessage $message
     * @return void
     */
    public function onMessage(AMQPMessage $message): void
    {
        $this->logger->debug($message->body);
    }

    /**
     * @return string
     */
    protected function getConsumerTag(): string
    {
        return self::CONSUMER_TAG;
    }

    /**
     * @return string
     */
    protected function getQueueName(): string
    {
        return self::QUEUE_NAME;
    }
}
