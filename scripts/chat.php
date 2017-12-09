<?php
/**
 * File: chat.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

require __DIR__ . '/../bootstrap/bootstrap.php';

use MSlwk\Chat\AMQP\RabbitMQMessagePublisher;
use MSlwk\Chat\Entity\LatestMessages\Reader;
use Ratchet\App;

const HOSTNAME_ENV = 'MSLWK_CHAT_HOSTNAME';
const PORT_ENV = 'MSLWK_CHAT_WEBSOCKET_PORT';
const IP_ENV = 'MSLWK_CHAT_ADDRESS';

$injector = new Auryn\Injector();

$AMQPPublisher = new RabbitMQMessagePublisher();
$latestMessagesReader = new Reader();
$injector->define(
    'MSlwk\Chat\Entity\Chat',
    [
        ':AMQPPublisher' => $AMQPPublisher,
        ':latestMessagesReader' => $latestMessagesReader
    ]
);

$app = new App(getenv(HOSTNAME_ENV), getenv(PORT_ENV), getenv(IP_ENV));
$app->route('/chat', $injector->make('MSlwk\Chat\Entity\Chat'));
$app->run();
