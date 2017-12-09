<?php
/**
 * File: redis_latest_messages.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

use MSlwk\Chat\Entity\LatestMessages\Writer;

require __DIR__ . '/../bootstrap/bootstrap.php';

$subscriber = new Writer();
$subscriber->start();
