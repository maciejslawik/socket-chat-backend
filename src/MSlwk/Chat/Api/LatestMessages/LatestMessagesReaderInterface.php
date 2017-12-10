<?php
/**
 * File: LatestMessagesReaderInterface.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Api\LatestMessages;

/**
 * Interface LatestMessagesReaderInterface
 * @package MSlwk\Chat\Api
 */
interface LatestMessagesReaderInterface extends LatestMessagesInterface
{
    /**
     * @return array
     */
    public function getLatestMessages(): array;
}
