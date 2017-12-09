<?php
/**
 * File: AMQPPublisherInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api\AMQP;

/**
 * Interface AMQPPublisherInterface
 * @package MSlwk\Chat\Api
 */
interface AMQPPublisherInterface extends AMQPInterface
{
    /**
     * @param string $message
     */
    public function publishMessage(string $message): void;
}
