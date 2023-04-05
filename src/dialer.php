<?php
namespace neffos;

use Workerman\Connection\AsyncTcpConnection;

class dialer
{
    private AsyncTcpConnection $ws;
    private string $connId;
    private array $handler;
    private array $event;

    /**
     * @param string $remote_address
     * @param array $commandHandle
     * @throws \Exception
     */
    public function __construct(string $remote_address, array $commandHandle)
    {
        $this->ws = new AsyncTcpConnection($remote_address);
        foreach ($commandHandle as $nameSpace => $event) {
            $this->handler[$nameSpace] = new handler($this->ws, $nameSpace, $event);
        }
        // ACK
        $this->ws->send('M');
    }

    public function NSConn() :AsyncTcpConnection {
        return $this->ws;
    }

    public function ID() :string {
        return $this->connId;
    }

    public function connect(string $nameSpace) :?handler {
        $msgId = msgId();
        // 记录一下connect namespace 时的消息id，方便找对应的 namespace handle 处理
        $this->event[$msgId] = $nameSpace;

        $message = new message();
        $this->ws->send($message->setMsgId($msgId)->setNamespace($nameSpace)->setEvent(events::$onNamespaceConnect)->marshal());
        return $this->handler[$nameSpace];
    }

    public function Message(string $message) {
        // echo "<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Message >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>\r\n";
        // echo $message."\r\n\r";

        $msg = (new message())->unMarshal($message);

        // send('M')
        if ($msg->getMsgId() == '' && $msg->getNamespace() == '' && $msg->getEvent() == '') {
            $this->connId = $message;
            return;
        }

        // connect to nameSpace
        if ($msg->getMsgId() != '' && $msg->getNamespace() == '' && $msg->getEvent() == '') {
            $msg = (new message())->setMsgId('')->setNamespace($this->event[$msg->getMsgId()])->setEvent(events::$onNamespaceConnect);
            unset($this->event[$msg->getMsgId()]);
        }

        $this->handler[$msg->getNamespace()]->event($msg);

    }
}
