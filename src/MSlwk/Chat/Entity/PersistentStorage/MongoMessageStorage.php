<?php
/**
 * File: MongoMessageStorage.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

namespace MSlwk\Chat\Entity\PersistentStorage;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Manager;
use MSlwk\Chat\AMQP\AbstractRabbitMQSubscriber;
use MSlwk\Chat\Api\PersistentStorage\PersistentStorageInterface;
use MSlwk\Chat\Entity\Message;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class MongoMessageStorage
 * @package MSlwk\Chat\Entity\PersistentStorage
 */
class MongoMessageStorage extends AbstractRabbitMQSubscriber implements PersistentStorageInterface
{
    const CONSUMER_TAG = 'mongo_message_persistence';
    const QUEUE_NAME = 'mongo_message_persistence';

    const MESSAGES_NUMBER = 100;

    const MONGO_HOST_ENV = 'MONGODB_HOSTNAME';
    const MONGO_PORT_ENV = 'MONGODB_PORT';
    const MONGO_USER_ENV = 'MONGODB_USER';
    const MONGO_PASS_ENV = 'MONGODB_PASSWORD';

    const MONGO_DB = 'db.messages';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var BulkWrite
     */
    protected $bulk;

    /**
     * @var int
     */
    protected $storageCounter;

    /**
     * MongoMessageStorage constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $mongoAddress = getenv(self::MONGO_HOST_ENV) . ':' . getenv(self::MONGO_PORT_ENV);
        $this->manager = new Manager(
            "mongodb://{$mongoAddress}",
            [
                'username' => getenv(self::MONGO_USER_ENV),
                'password' => getenv(self::MONGO_PASS_ENV)
            ]
        );
        $this->bulk = new BulkWrite();
        $this->storageCounter = 0;
    }

    /**
     * @param Message $message
     * @return void
     */
    public function saveMessage(Message $message): void
    {
        $document = [
            'message' => $message->getMessage(),
            'nickname' => $message->getNickname(),
            'timestamp' => $message->getTime()
        ];
        $this->bulk->insert($document);

        if (++$this->storageCounter >= self::MESSAGES_NUMBER) {
            $this->manager->executeBulkWrite(self::MONGO_DB, $this->bulk);
            $this->bulk = new BulkWrite();
            $this->storageCounter = 0;
        }
    }

    /**
     * @param AMQPMessage $message
     * @return void
     */
    public function onMessage(AMQPMessage $message): void
    {
        $messageData = json_decode($message->body);
        $messageObject = new Message();
        $messageObject->setMessage($messageData->message);
        $messageObject->setNickname($messageData->nickname);
        $this->saveMessage($messageObject);
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
