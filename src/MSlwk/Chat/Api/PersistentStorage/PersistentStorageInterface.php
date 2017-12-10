<?php
/**
 * File: PersistentStorageInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api\PersistentStorage;

use MSlwk\Chat\Entity\Message;

/**
 * Interface PersistentStorageInterface
 * @package MSlwk\Chat\Api\PersistentStorage
 */
interface PersistentStorageInterface
{
    /**
     * @param Message $message
     * @return void
     */
    public function saveMessage(Message $message): void;
}
