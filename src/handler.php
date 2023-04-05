<?php
namespace neffos;

use Workerman\Connection\AsyncTcpConnection;

class handler
{
    private AsyncTcpConnection $conn;
    private string $nameSpace;
    // eventName => function
    private array $event;

    public function __construct(AsyncTcpConnection $conn, string $nameSpace, array $event)
    {
        $this->conn = $conn;
        $this->nameSpace = $nameSpace;
        $this->event = $event;
    }

    public function event(message $message)
    {
        $msgId = $message->getMsgId();
        $event = $message->getEvent();

        if ($message->getMsgId() != '') {
            call_user_func($this->event[$msgId], $message);
            unset($this->event[$msgId]);
            return;
        }
        if (isset($this->event[$event])){
            call_user_func($this->event[$event], $this, $message);
        }
    }

    public function NSConn() :AsyncTcpConnection {
        return $this->conn;
    }

    public function Ask(callable $call, string $event, ?string $body) {
        $msgId = msgId();
        $message = new message();
        $message->setMsgId($msgId)->setNamespace($this->nameSpace)->setEvent($event)->setBody($body);
        $this->event[$msgId] = $call;
        $this->conn->send($message->marshal());
    }
    public function Emit(string $event, ?string $body) {
        $message = new message();
        $message->setNamespace($this->nameSpace)->setEvent($event)->setBody($body);
        $this->conn->send($message->marshal());
    }
}
