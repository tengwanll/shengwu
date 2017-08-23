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
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\CarModel;
use Mirror\ApiBundle\Model\GoodsModel;
use Mirror\ApiBundle\Model\OrderGoodsModel;
use Mirror\ApiBundle\Model\OrdersModel;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Mirror\ApiBundle\Model\UserModel;
use Mirror\ApiBundle\Util\Ucpaas;
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
    public function getList($pageable,$number,$time,$username,$status,$attr,$conn){
        $rr=new ReturnResult();
        if($time){
            $times=explode('~',$time);
            $beginTime=$times[0]?$times[0].' 00:00:00':'';
            $endTime=$times[1]?$times[1].' 23:59:59':'';
        }else{
            $beginTime='';
            $endTime='';
        }
        $list=$this->ordersModel->getList($pageable,$number,$beginTime,$endTime,$username,$status,$attr,$conn);
        $total=$this->ordersModel->getCount($number,$beginTime,$endTime,$username,$status,$attr,$conn);
        $arr=array();
        foreach($list as $order){
            $arr[]=array(
                'id'=>$order['id'],
                'number'=>$order['order_no'],
                'username'=>$order['username'],
                'price'=>$order['price'],
                'status'=>$order['status'],
                'goods'=>$order['name'],
                'count'=>$order['number'],
                'goodsNumber'=>$order['goods_number'],
                'totalPrice'=>$order['price']*$order['number'],
                'attr'=>isset($order['myAttr'])?$order['myAttr']:''
            );
        }
        $rr->result=array(
            'list'=>$arr,
            'total'=>$total[0]['total']
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
            $orderNumber=$this->ordersModel->getCountBy(array('<'=>array('createTime'=>date('Y-m-d').' 23:59:59'),'>'=>array('createTime'=>date('Y-m-d').' 00:00:00')));
            //每个商品形成一个订单
            foreach ($carId as $key=>$id){
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
                $order=$this->ordersModel->add($goodsPrice,$userId,$message,$orderNumber+$key+1);
                $this->orderGoodsModel->add($order->getId(),$goodsNumber,$goodsId,$goodsPrice);
                $this->carModel->delete($goods);
            }
            $this->ordersModel->getEntityManager()->commit();
        }catch (\Exception $e){
            $this->ordersModel->getEntityManager()->rollback();
            $rr->errno=Code::$create_order_fail;
            return $rr;
        }
        $admins=$this->userModel->getByParams(array('>'=>array('role'=>1)));
        $userId=array();
        foreach($admins->getIterator() as $user){
            $userId[]=$user->getId();
        }
        $rr->result=array('userId'=>$userId);
        return $rr;
    }

    /**
     * @param $orderId
     * @param $status
     * @return ReturnResult
     */
    public function changeStatus($orderId,$status,$message){
        $rr=new ReturnResult();
        $order=$this->ordersModel->getById($orderId);
        /**@var $order \Mirror\ApiBundle\Entity\Orders*/
        if(!$order){
            $rr->errno=Code::$order_not_exist;
            return $rr;
        }
        if($message){
            $order->setMessage($message);
        }
        $orderGoods=$this->orderGoodsModel->getOneByProperty('orderId',$orderId);
        $goods=$this->goodsModel->getById($orderGoods->getGoodsId());
        $name=$goods->getName();
        $order->setStatus($status);
        $this->ordersModel->save($order);
        if($status=='-1'){
            $status='未通过';
        }else if($status=='0'){
            $status='无效订单';
        }else if($status=='1'){
            $status='待审核';
        }else if($status=='2'){
            $status='订购中';
        }else if($status=='3'){
            $status='已到货';
        }else if($status=='4'){
            $status='已反馈';
        }
        $userId=$order->getUserId();
        $user=$this->userModel->getById($userId);
        $result=$this->ucpassSendMessage($user->getMobile(),$order->getOrderNo(),$status,$message,$name);
        $rr->result=array('userId'=>$userId);
        return $rr;
    }

    /**
     * @param $telephone
     * @param $orderNo
     * @param $status
     * @return mixed|string
     * @throws \Mirror\ApiBundle\Util\Exception
     */
    public function ucpassSendMessage($telephone,$orderNo, $status,$message,$name) {
        $options['accountsid'] = Constant::$UCPASS_ACCOUNT_SID;
        $options['token'] = Constant::$UCPASS_AUTH_TOKEN;
        $ucpass = new Ucpaas($options);
        $appId = Constant::$UCPAAS_APP_ID;
        $to = $telephone;
        if($status=='未通过'){
            $templateId = Constant::$UCPAAS_TEMPLATE_ID_2;
            $param = $orderNo.','.$name.','.$message;
        }else{
            $templateId = Constant::$UCPAAS_TEMPLATE_ID_1;
            $param = $orderNo.','.$name.','.$status;
        }
        return $ucpass->templateSMS($appId, $to, $templateId, $param);
    }

    /**
     * @param $orderId
     * @param $price
     * @return ReturnResult
     */
    public function changePrice($orderId,$price){
        $rr=new ReturnResult();
        $order=$this->ordersModel->getById($orderId);
        if($order){
            $order->setPrice($price);
            $this->ordersModel->save($order);
        }else{
            $rr->errno=Code::$order_not_exist;
        }
        return $rr;
    }

    public function changeStatusAll($orderIds,$status,$message){
        $rr=new ReturnResult();
        $userIds=array();
        foreach ($orderIds as $orderId){
            $order=$this->ordersModel->getById($orderId);
            /**@var $order \Mirror\ApiBundle\Entity\Orders*/
            if(!$order){
                continue;
            }
            if($message){
                $order->setMessage($message);
            }
            $orderGoods=$this->orderGoodsModel->getOneByProperty('orderId',$orderId);
            $goods=$this->goodsModel->getById($orderGoods->getGoodsId());
            $name=$goods->getName();
            $order->setStatus($status);
            $this->ordersModel->save($order);
            if($status=='-1'){
                $statusM='未通过';
            }else if($status=='0'){
                $statusM='无效订单';
            }else if($status=='1'){
                $statusM='待审核';
            }else if($status=='2'){
                $statusM='订购中';
            }else if($status=='3'){
                $statusM='已到货';
            }else if($status=='4'){
                $statusM='已反馈';
            }
            $userId=$order->getUserId();
            $userIds[]=$userId;
            $user=$this->userModel->getById($userId);
            $result=$this->ucpassSendMessage($user->getMobile(),$order->getOrderNo(),$statusM,$message,$name);
        }
        $userIds=array_unique($userIds);
        $rr->result=array('userId'=>$userIds);
        return $rr;
    }

    /**
     * @param $orderId
     * @return ReturnResult
     */
    public function delete($orderId){
        $rr=new ReturnResult();
        $order=$this->ordersModel->getById($orderId);
        if($order){
            $this->ordersModel->delete($order);
        }else{
            $rr->errno=Code::$order_not_exist;
            return $rr;
        }
        return $rr;
    }

    /**
     * @return ReturnResult
     */
    public function getOriginOrderNum(){
        $rr=new ReturnResult();
        $number=$this->ordersModel->getCountBy(array('status'=>1));
        $rr->result=array(
            'number'=>$number
        );
        return $rr;
    }
}