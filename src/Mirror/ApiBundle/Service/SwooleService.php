<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/9/26
 * Time: 10:07
 */

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Model\GoodsModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * @DI\Service("swoole_service")
 * Class SwooleService
 * @package Mirror\ApiBundle\Service
 */
class SwooleService
{
    private $_serv;
    public $goodsModel;

    /**
     * @InjectParams({
     *      "goodsModel"=@Inject("goods_model")
     * })
     * SwooleService constructor.
     * @param GoodsModel $goodsModel
     */
    public function __construct(GoodsModel $goodsModel)
    {
       $this->goodsModel=$goodsModel;
    }

    public function _prepare(){
        $this->_serv=new \Swoole\Server('120.27.5.26',9503);
        $this->_serv->set([
            'worker_num' => 1,
            'daemonize'=>false,
            'heartbeat_check_interval' => 120,
            'heartbeat_idle_time' => 242,
            'log_file'=>__DIR__.'/server.log'
        ]);
        $this->_serv->on('connect', [$this, 'onConnect']);
        $this->_serv->on('receive', [$this, 'onReceive']);
        $this->_serv->on('close', [$this, 'onClose']);
        $this->_serv->on("start", function ($serv){
            swoole_set_process_name('testsocket_server: master');
        });
        // 以下回调发生在Manager进程
        $this->_serv->on('ManagerStart', function ($serv){
            swoole_set_process_name('testsocket_server: manager');
        });
        $this->_serv->on('WorkerStart', function ($serv, $workerId){
            if($workerId >= $serv->setting['worker_num']) {
                swoole_set_process_name("testsocket_server: task");
            } else {
                swoole_set_process_name("testsocket_server: worker");
            }
        });
    }

    public function onConnect($serv, $fd){
        echo $fd.'连接成功';
    }

    public function onReceive($serv, $fd, $fromId, $data){
        $data=json_decode($data,true);
        switch ($data['event']){
            case 'test1':
                $message=$data['msg'];
                $goodsId=$data['goodsId'];
                $goods=$this->goodsModel->getById($goodsId);
                $name=$goods?$goods->getName():'';
                //$serv->send();
                echo date('Y-m-d H:i:s')."-测试一".$name."\r\n";
                break;
            case 'test2':
                $message=$data['msg'];
                $goodsId=$data['goodsId'];
                $goods=$this->goodsModel->getById($goodsId);
                $goodNumber=$goods?$goods->getGoodsNumber():'';
                //$serv->send();
                echo date('Y-m-d H:i:s')."-测试一".$goodNumber."\r\n";
                break;
            default:
                echo date('Y-m-d H:i:s').'-'.$data['event'].'-'.$data['msg']."\r\n";
        }
    }

}