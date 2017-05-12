<?php
/**
 * Created by PhpStorm.
 * User: tengwanll
 * Date: 2016/4/13
 * Time: 10:18
 */

namespace Mirror\ApiBundle\Util;


class Socket
{
    private $port;
    private $ip;

    /**
     * Socket constructor.
     * @param $port
     * @param $ip
     */
    public function __construct($port, $ip)
    {
        $this->port = $port;
        $this->ip = $ip;
    }

    public function setIp($ip){
        $this->ip=$ip;
    }

    public function client($message){
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            socket_close($socket);
            return false;
        }
        $result = socket_connect($socket, $this->ip, $this->port);
        if ($result < 0) {
            socket_close($socket);
            return false;
        }
        $message=json_encode($message);

        if(!socket_write($socket, $message, strlen($message))) {
            socket_close($socket);
            return false;
        }
        socket_close($socket);
        return true;
//        while($out = socket_read($socket, 8192)) {
//            echo "接收服务器回传信息成功！\n";
//            echo "接受的内容为:",$out;
//        }
    }
}