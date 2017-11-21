<?php
/**
 * File: index.php
 *
 * @author      Maciej Sławik <maciekslawik@gmail.com>
 * Github:      https://github.com/maciejslawik
 */

// When auth is turned on, then pass in these as the second parameters to the client.
$options = [
    'username' => 'root',
    'password' => 'password'
];

try {
    $manager = new \MongoDB\Driver\Manager('mongodb://mongodb:27017', $options);
    $bulk = new MongoDB\Driver\BulkWrite;
//    $bulk->insert(['x' => 1]);
//    $bulk->insert(['x' => 2]);
//    $bulk->insert(['x' => 3]);
//    $manager->executeBulkWrite('db.collection', $bulk);

    $filter = ['x' => ['$gt' => 1]];
    $options = [
        'projection' => ['_id' => 0],
        'sort' => ['x' => -1],
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db.collection', $query);

    foreach ($cursor as $document) {
        var_dump($document);
    }


} catch (Exception $error) {
    echo $error->getMessage();
    die(1);
}
$redis = new Redis();

$redis->connect('redis', 6379);
var_dump($redis->auth('password123'));
echo "Connection to server sucessfully";
//$redis->lpush("tutorial-list", "Redis");
//$redis->lpush("tutorial-list", "Mongodb");
//$redis->lpush("tutorial-list", "Mysql");
//
//// Get the stored data and print it
//$arList = $redis->lrange("tutorial-list", 0, 5);
//echo "Stored string in redis:: ";
//print_r($arList);
echo "Server is running: " . $redis->ping();


require_once('vendor/autoload.php');


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'router';
$queue = 'msgs';
$connection = new AMQPStreamConnection(
    'rabbitmq',
    5672,
    'user',
    'pass',
    'vhost'
);
$channel = $connection->channel();

/*
    The following code is the same both in the consumer and the producer.
    In this way we are sure we always have a queue to consume from and an
        exchange where to publish messages.
*/
/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$channel->queue_declare($queue, false, true, false, false);
/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/
$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);
$messageBody = 'test message';
$message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($message, $exchange);
$channel->close();
$connection->close();
