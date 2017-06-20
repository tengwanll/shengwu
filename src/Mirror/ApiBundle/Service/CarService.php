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
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\CarModel;
use Mirror\ApiBundle\Model\GoodsModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Model\OrdersModel;
use Mirror\ApiBundle\Model\SystemSettingModel;
use Mirror\ApiBundle\Model\SortModel;
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
    private $sortModel;
    private $systemSettingModel;

    /**
     * @InjectParams({
     *     "carModel"=@Inject("car_model"),
     *     "goodsModel"=@Inject("goods_model"),
     *     "fileService"=@Inject("file_service"),
     *     "systemSettingModel"=@Inject("system_setting_model"),
     *     "sortModel"=@Inject("sort_model"),
     *     "ordersModel"=@Inject("orders_model")
     * })
     * CarService constructor.
     * @param CarModel $carModel
     * @param GoodsModel $goodsModel
     * @param OrdersModel $ordersModel
     * @param FileService $fileService
     * @param SortModel $sortModel
     * @param SystemSettingModel $systemSettingModel
     */
    public function __construct(CarModel $carModel,GoodsModel $goodsModel,OrdersModel $ordersModel,FileService $fileService,SortModel $sortModel,SystemSettingModel $systemSettingModel)
    {
        $this->carModel=$carModel;
        $this->goodsModel=$goodsModel;
        $this->ordersModel=$ordersModel;
        $this->fileService=$fileService;
        $this->systemSettingModel=$systemSettingModel;
        $this->sortModel=$sortModel;
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
        foreach($car->getIterator() as $goodsCar){
            /**@var $goodsCar \Mirror\ApiBundle\Entity\GoodsCar*/
            $goodsId=$goodsCar->getGoodsId();
            $goods=$this->goodsModel->getById($goodsId);
            $arr[]=array(
                'id'=>$goodsCar->getId(),
                'goodsName'=>$goods?$goods->getName():'',
                'number'=>$goodsCar->getNumber(),
                'price'=>$goodsCar->getPrice(),
                'goodsId'=>$goodsCar->getGoodsId()
            );
        }
        $rr->result=array('list'=>$arr,'total'=>$car->count());
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

    /**
     * @param $result
     * @param $userId
     * @return ReturnResult
     */
    public function import($file,$userId,$conn){
        $rr=new ReturnResult();
        if(pathinfo($file,PATHINFO_EXTENSION )!='xlsx'){
            $rr->errno=Code::$file_not_right_excel;
            return $rr;
        }
        define('PHPEXCEL', dirname(__FILE__) . '/../Util/');
        require(PHPEXCEL . 'Import.php');
        $result=array();
        for ($row = 2;$row <= $highestRow;$row++)
        {
            //注意highestColumnIndex的列数索引从0开始
            $name=$objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
            if(!$name){
                $rr->errno=Code::$file_name_null;
                $rr->result=array(
                    'rows'=>$row,
                    'col'=>1
                );
                return $rr;
            }
            $sortName=$objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
            $sort=$this->sortModel->getOneByCriteria(array('name'=>$sortName,'status'=>Constant::$status_normal));
            /**@var $sort \Mirror\ApiBundle\Entity\Sort*/
            if(!$sort){
                $rr->errno=Code::$file_sort_not_exist;
                $rr->result=array(
                    'rows'=>$row,
                    'col'=>2
                );
                return $rr;
            }
            $price=$sortName=$objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
            if(!$price){
                $rr->errno=Code::$file_price_null;
                $rr->result=array(
                    'rows'=>$row,
                    'col'=>3
                );
                return $rr;
            }
            $description=$sortName=$objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
            $number=$sortName=$objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            $attrs=$sortName=$objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
            $result[]=array(
                'name'=>$name,
                'sort'=>$sort->getId(),
                'price'=>$price,
                'number'=>$number?$number:1,
                'description'=>$description,
                'attrs'=>$attrs
            );
        }
        foreach ($result as $val){
            $name=$val['name'];
            $goods=$this->goodsModel->getOneByCriteria(array('name'=>$name,'status'=>Constant::$status_normal));
            /**@var $goods \Mirror\ApiBundle\Entity\Goods*/
            if($goods){
                //如果已经存在商品
                $goodsId=$goods->getId();
                $carGoods=$this->carModel->getOneByCriteria(array('userId'=>$userId,'goodsId'=>$goodsId,'status'=>Constant::$status_normal));
                /**@var $carGoods \Mirror\ApiBundle\Entity\GoodsCar*/
                if($carGoods){
                    //购物车中已经有该商品,直接在购物车中添加数量和价格
                    $numberAll=$carGoods->getNumber()+$val['number'];
                    $carGoods->setNumber($numberAll);
                    $carGoods->setPrice($carGoods->getPrice()+($carGoods->getPrice()/$carGoods->getNumber())*$numberAll);
                    $this->carModel->save($carGoods);
                }else{
                    //购物车中没有该商品,添加进购物车
                    $this->carModel->add($userId,$goodsId,$val['number'],$val['price']);
                }
            }else{
                //没有该商品,添加到商品表中,然后添加到购物车
                $goods=$this->goodsModel->add($name,$val['sort'],$val['price'],$val['description'],$val['attrs'],$conn);
                $goodsId=$goods['goodsId'];
                $this->carModel->add($userId,$goodsId,$val['number'],$val['price']);
            }
        }
        return $rr;
    }
}