<?php
/**
 * File: LatestMessagesInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api\LatestMessages;

/**
 * Interface LatestMessagesInterface
 * @package MSlwk\Chat\Api\LatestMessages
 */
interface LatestMessagesInterface
{
    const REDIS_HOST_ENV = 'REDIS_HOST';
    const REDIS_PORT_ENV = 'REDIS_PORT';
    const REDIS_PASSWORD_ENV = 'REDIS_PASSWORD';

    const MESSAGES_NUMBER = 100;
    const REDIS_KEY = 'chat_latest_messages';
}
