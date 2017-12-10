<?php
/**
 * File: AMQPInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api\AMQP;

/**
 * Interface AMQPInterface
 * @package MSlwk\Chat\Api\AMQP
 */
interface AMQPInterface
{
    const RABBITMQ_HOSTNAME_ENV = 'RABBITMQ_HOST';
    const RABBITMQ_PORT_ENV = 'RABBITMQ_MAIN_PORT';
    const RABBITMQ_USERNAME_ENV = 'RABBITMQ_USER';
    const RABBITMQ_PASSWORD_ENV = 'RABBITMQ_PASS';
    const RABBITMQ_QUEUE_ENV = 'RABBITMQ_QUEUE';
    const RABBITMQ_EXCHANGE_ENV = 'RABBITMQ_EXCHANGE';
    const RABBITMQ_VHOST_ENV = 'RABBITMQ_VHOST';
}
