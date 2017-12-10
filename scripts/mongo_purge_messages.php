<?php
/**
 * File: mongo_purge_messages.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */


use MSlwk\Chat\Entity\PersistentStorage\MongoMessageStorage;

require __DIR__ . '/../bootstrap/bootstrap.php';

$subscriber = new MongoMessageStorage();
$subscriber->start();
