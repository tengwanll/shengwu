<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/15
 * Time: 16:07
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Util\Helper;

/**
 * @DI\Service("hpv_model")
 * Class HpvModel
 * @package Mirror\ApiBundle\Model
 */
class HpvModel
{
    public function getList($orderNo,$conn,$pageable){
        $sql="select o.order_no,o.id,o.name,o.price,o.number,o.pay_time,o.status,o.user_age,o.user_name,o.is_married,u.telephone from weixin.orders o,weixin.user u ";
        $where=array('o.status >1','o.user_id=u.id');
        if($orderNo){
            $where[]='o.order_no like %'.$orderNo.'%';
        }
        $sql=Helper::makeQueryString($sql,$where);
        if($pageable){
            if ($pageable) {
                $page=$pageable->getPage();
                $rows=$pageable->getRows();
                $start=($page-1)*$rows;
                $sql.= " limit $start,$rows ";
            }
        }
        return $conn->fetchAll($sql);
    }

    public function getCount($orderNo,$conn){
        $sql="select count(id) as total from weixin.orders ";
        $where=array('status >1');
        if($orderNo){
            $where[]='order_no like %'.$orderNo.'%';
        }
        $sql=Helper::makeQueryString($sql,$where);
        return $conn->fetchAll($sql);
    }
}