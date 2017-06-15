<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/5
 * Time: 11:59
 */

namespace Mirror\ApiBundle\Service;


use JMS\DiExtraBundle\Annotation as DI;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Model\CarModel;
use Mirror\ApiBundle\Model\GoodsModel;
use Mirror\ApiBundle\Model\OrderGoodsModel;
use Mirror\ApiBundle\Model\OrdersModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Model\UserModel;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Think\Exception;

/**
 * @DI\Service("order_service")
 * Class OrderService
 * @package Mirror\ApiBundle\Service
 */
class OrderService
{
    private $ordersModel;
    private $userModel;
    private $orderGoodsModel;
    private $goodsModel;
    private $carModel;

    /**
     * @InjectParams({
     *     "orderModel"=@Inject("orders_model"),
     *     "userModel"=@Inject("user_model"),
     *     "orderGoodsModel"=@Inject("order_goods_model"),
     *     "goodsModel"=@Inject("goods_model"),
     *     "carModel"=@Inject("car_model")
     * })
     * OrderService constructor.
     * @param OrdersModel $ordersModel
     * @param UserModel $userModel
     * @param OrderGoodsModel $orderGoodsModel
     * @param GoodsModel $goodsModel
     * @param CarModel $carModel
     */
    public function __construct(OrdersModel $ordersModel,UserModel $userModel,OrderGoodsModel $orderGoodsModel,GoodsModel $goodsModel,CarModel $carModel)
    {
        $this->ordersModel=$ordersModel;
        $this->userModel=$userModel;
        $this->orderGoodsModel=$orderGoodsModel;
        $this->goodsModel=$goodsModel;
        $this->carModel=$carModel;
    }

    /**
     * @param $pageable
     * @param $number
     * @param $beginTime
     * @param $endTime
     * @param $username
     * @param $status
     * @return ReturnResult
     */
    public function getList($pageable,$number,$beginTime,$endTime,$username,$status){
        $rr=new ReturnResult();
        $list=$this->ordersModel->getList($pageable,$number,$beginTime,$endTime,$username,$status);
        $arr=array();
        foreach($list->getIterator() as $orderVal){
            $order=$orderVal[0];
            /**@var $order \Mirror\ApiBundle\Entity\Orders*/
            $arr[]=array(
                'id'=>$order->getId(),
                'number'=>$order->getOrderNo(),
                'createTime'=>$order->getCreateTime()->format('Y-m-d H:i:s'),
                'username'=>$orderVal['username'],
                'price'=>$order->getPrice(),
                'status'=>$order->getStatus(),
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'total'=>$list->count()
        );
        return $rr;
    }

    /**
     * @param $orderId
     * @return ReturnResult
     */
    public function getInfo($orderId){
        $rr=new ReturnResult();
        $order=$this->ordersModel->getById($orderId);
        if(!$order){
            $rr->errno=Code::$order_not_exist;
            return $rr;
        }
        /**@var $order \Mirror\ApiBundle\Entity\Orders*/
        $userId=$order->getUserId();
        $user=$this->userModel->getById($userId);
        $arr=array(
            'id'=>$order->getId(),
            'number'=>$order->getOrderNo(),
            'createTime'=>$order->getCreateTime()->format('Y-m-d H:i:s'),
            'username'=>$user?$user->getUsername():'',
            'price'=>$order->getPrice(),
            'message'=>$order->getMessage(),
            'status'=>$order->getStatus()
        );
        $rr->result=$arr;
        return $rr;
    }

    /**
     * @param $pageable
     * @param $orderId
     * @return ReturnResult
     */
    public function getOrderGoods($pageable,$orderId){
        $rr=new ReturnResult();
        $orderGoodsList=$this->orderGoodsModel->getByParams(array('status'=>1,'orderId'=>$orderId),$pageable);
        $arr=array();
        foreach($orderGoodsList as $orderGoods){
            /**@var $orderGoods \Mirror\ApiBundle\Entity\OrderGoods*/
            $goodsId=$orderGoods->getGoodsId();
            $goods=$this->goodsModel->getById($goodsId);
            $arr[]=array(
                'id'=>$orderGoods->getId(),
                'goodsId'=>$orderGoods->getGoodsId(),
                'goodsName'=>$goods?$goods->getName():'',
                'number'=>$orderGoods->getNumber(),
                'price'=>$orderGoods->getPrice(),
                'status'=>$orderGoods->getStatus(),
                'createTime'=>$orderGoods->getCreateTime()->format('Y-m-d H:i:s')
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'total'=>$orderGoodsList->count()
        );
        return $rr;
    }

    /**
     * @param $carId
     * @param $price
     * @param $userId
     * @param $message
     * @return ReturnResult
     */
    public function create($carId,$price,$userId,$message){
        $rr=new ReturnResult();
        try{
            $this->ordersModel->getEntityManager()->beginTransaction();
            $order=$this->ordersModel->add($price,$userId,$message);
            foreach ($carId as $id){
                $goods=$this->carModel->getById($id);
                if(!$goods){
                    $this->ordersModel->getEntityManager()->rollback();
                    $rr->errno=Code::$create_order_fail;
                    return $rr;
                }
                /**@var $goods \Mirror\ApiBundle\Entity\GoodsCar*/
                $goodsNumber=$goods->getNumber();
                $goodsId=$goods->getGoodsId();
                $goodsPrice=$goods->getPrice();
                $this->orderGoodsModel->add($order->getId(),$goodsNumber,$goodsId,$goodsPrice);
                $this->carModel->delete($goods);
            }
            $this->ordersModel->getEntityManager()->commit();
        }catch (\Exception $e){
            $this->ordersModel->getEntityManager()->rollback();
            $rr->errno=Code::$create_order_fail;
            return $rr;
        }
        return $rr;
    }
}