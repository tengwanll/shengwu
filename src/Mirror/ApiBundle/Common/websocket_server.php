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
            'daemonize'=>true,
            'heartbeat_check_interval' => 120,
            'heartbeat_idle_time' => 242,
            'log_file'=>__DIR__.'/server.log'
        ]);
        $this->_serv->on('open', [$this, 'onOpen']);
        $this->_serv->on('message', [$this, 'onMessage']);
        $this->_serv->on('close', [$this, 'onClose']);
        $this->_serv->on("start", function ($serv){
            swoole_set_process_name('websocket_server: master');
        });
        // 以下回调发生在Manager进程
        $this->_serv->on('ManagerStart', function ($serv){
            swoole_set_process_name('websocket_server: manager');
        });
        $this->_serv->on('WorkerStart', function ($serv, $workerId){
            if($workerId >= $serv->setting['worker_num']) {
                swoole_set_process_name("websocket_server: task");
            } else {
                swoole_set_process_name("websocket_server: worker");
            }
        });
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
                    echo date('Y-m-d H:i:s')."-您有订单状态更改为".$message."\r\n";            
		}
                break;
            case 'order':
                $userId=$data['userId'];
                if(isset($this->userFd[$userId])){
                    $serv->push($this->userFd[$userId], "有一个订单需要您审核");
		    echo date('Y-m-d H:i:s')."-有一个订单需要您审核\r\n";
                }
                break;
	    case 'heart':
		break;
	    default:
		echo date('Y-m-d H:i:s').'-'.$data['event'].'-'.$data['msg']."\r\n";
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
