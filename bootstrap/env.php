<?php
/**
 * File: env.php
 * @author Maciej Sławik <maciekslawik@gmail.com>
 */

use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__ . '/..');
$dotenv->load();
