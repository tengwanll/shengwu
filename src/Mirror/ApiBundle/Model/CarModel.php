<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:13
 */

namespace Mirror\ApiBundle\Model;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Entity\GoodsCar;

/**
 * @DI\Service("car_model",parent="base_model")
 * Class CarModel
 * @package Mirror\ApiBundle\Model
 */
class CarModel extends BaseModel
{
    private $repositoryName = 'MirrorApiBundle:GoodsCar';

    public function getRepositoryName() {
        return $this->repositoryName;
    }

    /**
     * @param $userId
     * @param $goodsId
     * @param $number
     * @param $price
     * @return bool|GoodsCar
     */
    public function add($userId,$goodsId,$number,$price){
        $date=new \DateTime();
        $car=new GoodsCar();
        $car->setUserId($userId);
        $car->setGoodsId($goodsId);
        $car->setNumber($number);
        $car->setPrice($price*$number);
        $car->setStatus(Constant::$status_normal);
        $car->setCreateTime($date);
        $car->setUpdateTime($date);
        if($this->save($car)){
            return $car;
        }else{
            return false;
        }
    }
}