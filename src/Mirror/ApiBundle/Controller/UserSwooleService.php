<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/7/31
 * Time: 15:48
 */

namespace Mirror\ApiBundle\Controller;


class UserSwooleService
{
    private $_serv;
    public function __construct()
    {
        $this->_serv = new \swoole_websocket_server("127.0.0.1", 9501);
        $this->_serv->set([
            'worker_num' => 1,
            'heartbeat_check_interval' => 30,
            'heartbeat_idle_time' => 62,
        ]);
        $this->_serv->on('open', [$this, 'onOpen']);
        $this->_serv->on('message', [$this, 'onMessage']);
        $this->_serv->on('close', [$this, 'onClose']);
    }

    public function onOpen($serv,$request){
        $userId=isset($_SESSION['userId'])?$_SESSION['userId']:0;
        if(!$userId){
            $this->_serv->close($request->fd);
        }else{
            echo 'connect';
        }
    }

    public function onMessage($serv, $frame){
        echo $frame->data;
    }

    public function onClose(){

    }

    public function start()
    {
        $this->_serv->start();
    }
}
$server=new UserSwooleService();
$server->start();