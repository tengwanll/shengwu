<?php

class WebSocketServer
{
    private $_serv;
    private $userFd=array();

    public function __construct()
    {
        $this->_serv = new swoole_websocket_server("120.27.5.26", 9501);
        $this->_serv->set([
            'worker_num' => 1,
            'heartbeat_check_interval' => 600,
            'heartbeat_idle_time' => 1800,
        ]);
        $this->_serv->on('open', [$this, 'onOpen']);
        $this->_serv->on('message', [$this, 'onMessage']);
        $this->_serv->on('close', [$this, 'onClose']);
    }

    /**
     * @param $serv
     * @param $request
     */
    public function onOpen($serv, $request)
    {
        $userId=$request->get['userId']?$request->get['userId']:0;
        $fd=$request->fd;
        if($userId){
            if(isset($this->userFd[$userId])){
                $oldFd=$this->userFd[$userId];
                $this->_serv->close($oldFd);
            }
            $this->userFd[$userId]=$fd;
        }else{
            $this->_serv->close($fd);
        }
    }

    /**
     * @param $serv
     * @param $frame
     */
    public function onMessage($serv, $frame)
    {
        $data=json_decode($frame->data,true);
        switch ($data['event']){
            case 'status':
                $message=$data['msg'];
                $userId=$data['userId'];
                if(isset($this->userFd[$userId])){
                    $serv->push($this->userFd[$userId], "您有订单状态更改为".$message);
                }
                break;
        }
    }
    public function onClose($serv, $fd)
    {
        $key=array_search($fd,$this->userFd);
        unset($this->userFd[$key]);
    }

    public function start()
    {
        $this->_serv->start();
    }
}

$server = new WebSocketServer;
$server->start();
