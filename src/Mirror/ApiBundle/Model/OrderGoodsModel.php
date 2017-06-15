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
use Mirror\ApiBundle\Entity\OrderGoods;
use Mirror\ApiBundle\Util\QueryHelper;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @DI\Service("order_goods_model",parent="base_model")
 * Class OrderModel
 * @package Mirror\ApiBundle\Model
 */
class OrderGoodsModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:OrderGoods';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $orderId
     * @param $goodsNumber
     * @param $goodsId
     * @param $goodsPrice
     * @return bool|OrderGoods
     */
    public function add($orderId,$goodsNumber,$goodsId,$goodsPrice){
        $date=new \DateTime();
        $orderGoods=new OrderGoods();
        $orderGoods->setPrice($goodsPrice);
        $orderGoods->setGoodsId($goodsId);
        $orderGoods->setNumber($goodsNumber);
        $orderGoods->setOrderId($orderId);
        $orderGoods->setStatus(Constant::$status_normal);
        $orderGoods->setCreateTime($date);
        $orderGoods->setUpdateTime($date);
        if($this->save($orderGoods)){
            return $orderGoods;
        }else{
            return false;
        }
    }
}