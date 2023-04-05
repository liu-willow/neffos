<?php

use neffos\dialer;
use neffos\events;
use neffos\handler;
use neffos\message;
use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

require_once __DIR__ . '/vendor/autoload.php';


$task = new Worker();
$task->count = 1;


$client = null;

$task->onWorkerStart = function($task) use(&$client)
{
    $dialer = new dialer('ws://192.168.2.199:17002/websocket',[
        'index' => [
            events::$onNamespaceConnect => function(handler $handler, message $message) {
                echo "---------------------------------- index _OnNamespaceConnect -----------------------------\r\n\r\n";
                $handler->Emit('Chat', '桀桀桀');
            },
            "chat" => function(handler $handler, message $message) {
                echo "reply-Broadcast msg: ".$message->getBody()."\r\n\r\n";
            },
        ],
    ]);
    $client = $dialer->NSConn();

    $client->onConnect = function(AsyncTcpConnection $client) use ($dialer)
    {
        echo "connect success\n";
        $indexHandle = $dialer->connect('index');
        $indexHandle->Ask(function (message $msg) use($dialer) {
            if ($msg->getErr()) {
                echo '------------- chat ask error: '.$msg->getErr()."\r\n";
            } else {
                echo '------------- chat ask body: '.$msg->getBody()."\r\n";
            }
            echo "ID: ".$dialer->ID()."\r\n\r\n";
        },'Chat', 'chat ask');
    };
    $client->onMessage = function(AsyncTcpConnection $client, $message) use($dialer)
    {
        $dialer->Message($message);
    };
    $client->onClose = function(AsyncTcpConnection $client)
    {
        echo "connection closed\n";
        $client->close();

    };
    $client->onError = function(AsyncTcpConnection $client, $code, $msg)
    {
        echo "Error code:$code msg:$msg\n";
    };
    $client->connect();
};

$task->onWorkerStop = function ($task) use (&$client)
{
    $client->close();
};
// 运行worker
Worker::runAll();
