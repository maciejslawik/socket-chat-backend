<?php
/**
 * File: Message.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Model;

use JsonSerializable;

/**
 * Class Message
 * @package MSlwk\Model
 */
class Message implements JsonSerializable
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var int
     */
    private $time;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->time = time();
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->getMessage(),
            'nickname' => $this->getNickname(),
            'timestamp' => $this->getTime()
        ];
    }
}
