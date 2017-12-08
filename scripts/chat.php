<?php
/**
 * File: chat.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

require __DIR__ . '/../bootstrap/bootstrap.php';

use MSlwk\Model\Chat;
use Ratchet\App;

const HOSTNAME_ENV = 'MSLWK_CHAT_HOSTNAME';
const PORT_ENV = 'MSLWK_CHAT_WEBSOCKET_PORT';
const IP_ENV = 'MSLWK_CHAT_ADDRESS';

$app = new App(getenv(HOSTNAME_ENV), getenv(PORT_ENV), getenv(IP_ENV));
$app->route('/chat', new Chat);
$app->run();
