<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:10
 */

namespace Mirror\ApiBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Model\CarModel;
use Mirror\ApiBundle\Model\GoodsModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Model\OrdersModel;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;

/**
 * @DI\Service("car_service")
 * Class CarService
 * @package Mirror\ApiBundle\Service
 */
class CarService
{
    private $carModel;
    private $goodsModel;
    private $ordersModel;
    private $fileService;
    private $systemSettingModel;

    /**
     * @InjectParams({
     *     "carModel"=@Inject("car_model"),
     *     "goodsModel"=@Inject("goods_model"),
     *     "fileService"=@Inject("file_service"),
     *     "systemSettingModel"=@Inject("system_setting_model"),
     *     "ordersModel"=@Inject("orders_model")
     * })
     * CarService constructor.
     * @param CarModel $carModel
     * @param GoodsModel $goodsModel
     * @param OrdersModel $ordersModel
     * @param FileService $fileService
     * @param SystemSettingModel $systemSettingModel
     */
    public function __construct(CarModel $carModel,GoodsModel $goodsModel,OrdersModel $ordersModel,FileService $fileService,SystemSettingModel $systemSettingModel)
    {
        $this->carModel=$carModel;
        $this->goodsModel=$goodsModel;
        $this->ordersModel=$ordersModel;
        $this->fileService=$fileService;
        $this->systemSettingModel=$systemSettingModel;
    }

    /**
     * @param $pageable
     * @param $userId
     * @return ReturnResult
     */
    public function getList($pageable,$userId){
        $rr=new ReturnResult();
        $car=$this->carModel->getByParams(array('status'=>1,'userId'=>$userId),$pageable,'createTime');
        $arr=array();
        $totalPrice=0;
        foreach($car->getIterator() as $goodsCar){
            /**@var $goodsCar \Mirror\ApiBundle\Entity\GoodsCar*/
            $goodsId=$goodsCar->getGoodsId();
            $goods=$this->goodsModel->getById($goodsId);
            $totalPrice=$totalPrice+$goodsCar->getPrice();
            $arr[]=array(
                'id'=>$goodsCar->getId(),
                'goodsName'=>$goods?$goods->getName():'',
                'number'=>$goodsCar->getNumber(),
                'price'=>$goodsCar->getPrice(),
                'goodsId'=>$goodsCar->getGoodsId()
            );
        }
        $rr->result=array('list'=>$arr,'total'=>$car->count(),'totalPrice'=>$totalPrice);
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function addNumber($id){
        $rr=new ReturnResult();
        $car=$this->carModel->getById($id);
        /**@var $car \Mirror\ApiBundle\Entity\GoodsCar*/
        if(!$car){
            $rr->errno=Code::$car_not_exist;
            return $rr;
        }
        $number=$car->getNumber();
        $price=$car->getPrice();
        $car->setNumber($number+1);
        $car->setPrice($price+$price/$number);
        $this->carModel->save($car);
        return $rr;
    }

    /**
     * @param $id
     * @return ReturnResult
     */
    public function subNumber($id){
        $rr=new ReturnResult();
        $car=$this->carModel->getById($id);
        /**@var $car \Mirror\ApiBundle\Entity\GoodsCar*/
        if(!$car){
            $rr->errno=Code::$car_not_exist;
            return $rr;
        }
        if($car->getNumber()<=1){
            $rr->errno=Code::$number_not_right;
            return $rr;
        }
        $number=$car->getNumber();
        $price=$car->getPrice();
        $car->setNumber($number-1);
        $car->setPrice($price-$price/$number);
        $this->carModel->save($car);
        return $rr;
    }

    /**
     * @param $carId
     * @return ReturnResult
     */
    public function deleteCar($carId){
        $rr=new ReturnResult();
        $car=$this->carModel->getById($carId);
        /**@var $car \Mirror\ApiBundle\Entity\GoodsCar*/
        if(!$car){
            $rr->errno=Code::$car_not_exist;
            return $rr;
        }
        $this->carModel->delete($car);
        return $rr;
    }
}