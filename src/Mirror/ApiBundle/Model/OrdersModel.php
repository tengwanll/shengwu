<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/4
 * Time: 17:13
 */

namespace Mirror\ApiBundle\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\Orders;
use Mirror\ApiBundle\Util\OrderHelper;
use Mirror\ApiBundle\Util\QueryHelper;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("orders_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class OrdersModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:Orders';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $pageable
     * @param $number
     * @param $beginTime
     * @param $endTime
     * @param $username
     * @param $status
     * @return Paginator
     */
    public function getList($pageable,$number,$beginTime,$endTime,$username,$status,$attr,$conn){
        if($attr){
            $dql="select o.id,o.order_no,u.username,o.price,o.status,g.name,og.number,goods_number,column_get(g.attr,'$attr' as char) as myAttr from orders o,user u,order_goods og,goods g ";
        }else{
            $dql="select o.id,o.order_no,u.username,o.price,o.status,g.name,og.number,goods_number from orders o,user u,order_goods og,goods g ";
        }
        $where=array();
        $where[]=' o.user_id=u.id and og.order_id=o.id and og.goods_id=g.id ';
        if($number){
            $where[]=" o.order_no like '%$number%' ";
        }
        if($beginTime){
            $where[]=" o.create_time > '$beginTime' ";
        }
        if($endTime){
            $where[]=" o.create_time < '$endTime' ";
        }
        if($username){
            $where[]=" u.username like '%$username%' ";
        }
        if($status!='all'){
            $where[]=" o.status = $status ";
        }
        if($attr){
            $where[]="COLUMN_EXISTS(g.attr,'$attr')";
        }
        $dql=QueryHelper::makeQueryString($dql,$where);
        $dql.=' order by o.create_time desc ';

        if($pageable){
            $page=$pageable->getPage();
            $rows=$pageable->getRows();
            $start=($page-1)*$rows;
            $dql.= " limit $start,$rows ";
        }
        return $conn->fetchAll($dql);
    }

    public function getCount($number,$beginTime,$endTime,$username,$status,$attr,$conn){
        $dql="select count(o.id) as total from orders o,user u,order_goods og,goods g ";
        $where=array();
        $where[]=' o.user_id=u.id and og.order_id=o.id and og.goods_id=g.id ';
        if($number){
            $where[]=" o.order_no like '%$number%' ";
        }
        if($beginTime){
            $where[]=" o.create_time > '$beginTime' ";
        }
        if($endTime){
            $where[]=" o.create_time < '$endTime' ";
        }
        if($username){
            $where[]=" u.username like '%$username%' ";
        }
        if($status!='all'){
            $where[]=" o.status = $status ";
        }
        if($attr){
            $where[]="COLUMN_EXISTS(g.attr,'$attr')";
        }
        $dql=QueryHelper::makeQueryString($dql,$where);
        return $conn->fetchAll($dql);
    }

    /**
     * @param $price
     * @param $userId
     * @param $message
     * @return Orders
     */
    public function add($price,$userId,$message,$orderNumber){
        $orderNo=OrderHelper::generateTradeNo($orderNumber);
        $date=new \DateTime();
        $order=new Orders();
        $order->setPrice($price);
        $order->setMessage($message);
        $order->setOrderNo($orderNo);
        $order->setUserId($userId);
        $order->setStatus(Constant::$status_normal);
        $order->setUpdateTime($date);
        $order->setCreateTime($date);
        if($this->save($order)){
           return $order;
        }else{
            return false;
        }
    }
}