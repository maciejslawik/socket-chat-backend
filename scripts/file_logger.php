<?php
/**
 * File: file_logger.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

use MSlwk\Chat\Entity\FileMessageLogger;

require __DIR__ . '/../bootstrap/bootstrap.php';

$subscriber = new FileMessageLogger();
$subscriber->start();
