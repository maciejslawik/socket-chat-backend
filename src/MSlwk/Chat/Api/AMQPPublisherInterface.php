<?php
/**
 * File: AMQPPublisherInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api;

/**
 * Interface AMQPPublisherInterface
 * @package MSlwk\Chat\Api
 */
interface AMQPPublisherInterface
{
    /**
     * @param string $message
     */
    public function publishMessage(string $message): void;
}
