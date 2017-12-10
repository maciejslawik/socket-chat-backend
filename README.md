# Websocket chat backend #

Websocket chat backend prototype written in PHP 7.1. Messages are received via a WebSocket
and published to RabbitMQ. 
There are 3 AMQP consumers:
* Batch saving 100 messages to MongoDB
* Storing 100 latest messages in Redis for sending to new chat users
* Saving messages to a file log

Front-end application available [here](https://github.com/maciejslawik/socket-chat-frontend)


### Installation ###

The application requires docker and docker-compose.
To start:
* Set up parameters in ``.env``,
* ``composer instal`` 
* ``docker-compose up -d``
* From inside PHP container: ``/var/www/html/startup.sh``

### Technologies used ###
* PHP 7.1
* NginX
* MongoDB
* RabbitMQ
* Redis