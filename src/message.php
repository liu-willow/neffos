<?php
namespace neffos;


class message
{
    private string $messageSeparatorString = ';';

    private string $msgId = '';
    private string $namespace = '';
    private string $room = '';
    private string $event = '';
    private string $body = '';
    private string $err = '0';
    private string $isNoOp = '0';

    public function __construct(){}

    public function setMsgId(string $msgId) :self
    {
        $this->msgId = $msgId;
        return $this;
    }
    public function setNamespace(string $namespace) :self
    {
        $this->namespace = $namespace;
        return $this;
    }
    public function setRoom(string $room) :self
    {
        $this->room = $room;
        return $this;
    }
    public function setEvent(string $event) :self
    {
        $this->event = $event;
        return $this;
    }
    public function setErr(string $err) :self
    {
        $this->err = $err;
        return $this;
    }
    public function setIsNoOp(string $isNoOp) :self
    {
        $this->isNoOp = $isNoOp;
        return $this;
    }
    public function setBody(string $body) :self
    {
        $this->body = $body;
        return $this;
    }

    public function marshal() :string
    {
        return implode($this->messageSeparatorString, [$this->msgId, $this->namespace, $this->room, $this->event, $this->err, $this->isNoOp, $this->body]);
    }

    public function unMarshal(string $message) :self
    {
        $deserialize = explode($this->messageSeparatorString, $message);
        if (count($deserialize) > 3) {
            $this->msgId = $deserialize[0];
            $this->namespace = $deserialize[1];
            $this->room = $deserialize[2];
            $this->event = $deserialize[3];
            $this->err = $deserialize[4];
            $this->isNoOp = $deserialize[5];
            $this->body = $deserialize[6];
        }
        return $this;
    }

    public function getMsgId() :string
    {
        return $this->msgId;
    }
    public function getNamespace() :string
    {
        return $this->namespace ;
    }
    public function getRoom() :string
    {
        return $this->room;
    }
    public function getEvent() :string
    {
        return $this->event;
    }
    public function getErr() :string
    {
        return $this->err;
    }
    public function getIsNoOp() :string
    {
        return $this->isNoOp;
    }
    public function getBody() :string
    {
        return $this->body;
    }
}
