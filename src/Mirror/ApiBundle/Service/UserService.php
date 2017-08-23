<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2016/8/8
 * Time: 9:55
 */

namespace Mirror\ApiBundle\Service;

use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\Model\GoodsModel;
use Mirror\ApiBundle\Model\LogLoginModel;
use Mirror\ApiBundle\Model\OrderGoodsModel;
use Mirror\ApiBundle\Model\OrdersModel;
use Mirror\ApiBundle\Model\UserModel;
use Mirror\ApiBundle\ViewModel\Pageable;
use Mirror\ApiBundle\ViewModel\ReturnResult;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("user_service")
 * Class UserService
 * @package Mirror\ApiBundle\Service
 */
class UserService
{
    private $userModel;
    private $logLoginModel;
    private $fileService;
    private $telephoneCodeService;
    private $ordersModel;
    private $goodsModel;
    private $orderGoodsModel;
    /**
     * @InjectParams({
     *      "userModel" = @Inject("user_model"),
     *     "logLoginModel"=@Inject("log_login_model"),
     *     "fileService"=@Inject("file_service"),
     *     "orderModel"=@Inject("orders_model"),
     *     "goodsModel"=@Inject("goods_model"),
     *     "orderGoodsModel"=@Inject("order_goods_model")
     * })
     * UserService constructor.
     * @param UserModel $userModel
     * @param LogLoginModel $logLoginModel
     * @param FileService $fileService
     * @param TelephoneCodeService $telephoneCodeService
     * @param OrdersModel $ordersModel
     * @param GoodsModel $goodsModel
     * @param OrderGoodsModel $orderGoodsModel
     */
    public function __construct(UserModel $userModel,LogLoginModel $logLoginModel,TelephoneCodeService $telephoneCodeService,FileService $fileService,OrdersModel $ordersModel,GoodsModel $goodsModel,OrderGoodsModel $orderGoodsModel)
    {
        $this->userModel=$userModel;
        $this->logLoginModel=$logLoginModel;
        $this->telephoneCodeService=$telephoneCodeService;
        $this->fileService=$fileService;
        $this->ordersModel=$ordersModel;
        $this->goodsModel=$goodsModel;
        $this->orderGoodsModel=$orderGoodsModel;
    }

    /**
     * 登录
     * @param $telephone
     * @param $password
     * @param $ipAddress
     * @param $creditId
     * @param $propertyId
     * @param $rewardId
     * @return ReturnResult
     */
    public function login($telephone,$password,$ipAddress){
        $rr=new ReturnResult();
        $user=$this->userModel->getOneByCriteria(array('mobile'=>$telephone,'status'=>Constant::$status_normal));
        if(!$user){
            $rr->errno=Code::$user_not_exist;
            return $rr;
        }
        if($user->getStatus()==2){
            $rr->errno=Code::$user_forbidden;
            return $rr;
        }
        //判断密码
        if(md5($password)!=$user->getPassword()){
            $rr->errno=Code::$password_not_right;
            return $rr;
        }
        $photo=$this->fileService->getFullUrlById($user->getImage());
        //生成日志
        $this->logLoginModel->saveLogin($user->getId(),Constant::$login_type_admin,$ipAddress);
        $data=array(
            'userId'=>$user->getId(),
            'username'=>$user->getUsername(),
            'telephone'=>$user->getMobile(),
            'role'=>$user->getRole(),
            'photo'=>$photo
        );
        $rr->result=$data;
        return $rr;
    }

   public function getList($pageable,$username,$mobile){
       $rr=new ReturnResult();
       $arguments=array('status'=>1,'<>'=>array('role'=>3));
       if($username){
           $arguments['like']=array('username'=>'%'.$username.'%');
       }
       if($mobile){
           $arguments['like']=array('mobile'=>'%'.$mobile.'%');
       }
       $list=$this->userModel->getByParams($arguments,$pageable);
       $arr=array();
       foreach($list->getIterator() as $user){
           /**@var $user \Mirror\ApiBundle\Entity\User*/
           $imageId=$user->getImage();
           $image=$this->fileService->getFullUrlById($imageId);
           $userId=$user->getId();
           $pageable=new Pageable(1,1);
           $userLoginLog=$this->logLoginModel->getByPages(array('entityId'=>$userId),$pageable,'createTime desc');
            $arr[]=array(
                'id'=>$userId,
                'username'=>$user->getUsername(),
                'mobile'=>$user->getMobile(),
                'image'=>$image,
                'role'=>$user->getRole(),
                'status'=>$user->getStatus(),
                'createTime'=>$user->getCreateTime()->format('y-m-d'),
                'lastTime'=>$userLoginLog?$userLoginLog[0]->getCreateTime()->format('Y-m-d H:i:s'):''
            );
       }
       $rr->result=array(
           'list'=>$arr,
           'total'=>$list->count()
       );
       return $rr;
   }

    /**
     * @param $userId
     * @return ReturnResult
     */
    public function detail($userId){
        $rr=new ReturnResult();
        $user=$this->userModel->getById($userId);
        $imageId=$user->getImage();
        $image=$this->fileService->getFullUrlById($imageId);
        $arr=array(
            'userId'=>$user->getId(),
            'username'=>$user->getUsername(),
            'mobile'=>$user->getMobile(),
            'image'=>$image,
            'role'=>$user->getRole(),
            'status'=>$user->getStatus(),
            'createTime'=>$user->getCreateTime()->format('y-m-d'),
        );
        $rr->result=$arr;
        return $rr;
    }

    /**
     * @param $pageable
     * @param $userId
     * @return ReturnResult
     */
    public function getUserOrder($pageable,$userId){
        $rr=new ReturnResult();
        $argument=array(
            'userId'=>$userId,
        );
        $list=$this->ordersModel->getByParams($argument,$pageable);
        $arr=array();
        foreach($list->getIterator() as $order){
            /**@var $order \Mirror\ApiBundle\Entity\Orders*/
            $orderId=$order->getId();
            $orderGoods=$this->orderGoodsModel->getOneByProperty('orderId',$orderId);
            /**@var $orderGoods \Mirror\ApiBundle\Entity\OrderGoods*/
            $goodsName='';
            $goodsNumber='';
            $count=0;
            if($orderGoods){
                $goodsId=$orderGoods->getGoodsId();
                $goods=$this->goodsModel->getById($goodsId);
                /**@var $goods \Mirror\ApiBundle\Entity\Goods*/
                if($goods){
                    $goodsName=$goods->getName();
                    $goodsNumber=$goods->getGoodsNumber();
                }
                $count=$orderGoods->getNumber();
            }
            $arr[]=array(
                'id'=>$orderId,
                'number'=>$order->getOrderNo(),
                'price'=>$order->getPrice(),
                'status'=>$order->getStatus(),
                'goods'=>$goodsName,
                'count'=>$count,
                'goodsNumber'=>$goodsNumber,
                'totalPrice'=>$order->getPrice()*$count,
            );
        }
        $rr->result=array('list'=>$arr,'total'=>$list->count());
        return $rr;
    }

    /**
     * @param $userId
     * @param $status
     * @return ReturnResult
     */
    public function updateStatus($userId,$status){
        $rr=new ReturnResult();
        $user=$this->userModel->getById($userId);
        /**@var $user \Mirror\ApiBundle\Entity\User*/
        if(!$user){
            $rr->errno=Code::$user_not_exist;
            return $rr;
        }
        $user->setStatus($status);
        $this->userModel->save($user);
        return $rr;
    }

    /**
     * @param $userId
     * @return ReturnResult
     */
    public function resetPwd($userId){
        $rr=new ReturnResult();
        $user=$this->userModel->getById($userId);
        /**@var $user \Mirror\ApiBundle\Entity\User*/
        if(!$user){
            $rr->errno=Code::$user_not_exist;
            return $rr;
        }
        $password=md5('a123456');
        $user->setPassword($password);
        $this->userModel->save($user);
        return $rr;
    }

    /**
     * @param $user
     * @return ReturnResult
     */
    public function create($user){
        $rr=new ReturnResult();
        $data=$this->userModel->getOneByCriteria(array('mobile'=>$user->getMobile()));
        if($data){
            $rr->errno=Code::$mobile_already_exist;
            return $rr;
        }
        $this->userModel->add($user);
        return $rr;
    }

    /**
     * @param $userId
     * @param $mobile
     * @param $username
     * @param $oldPassword
     * @param $newPassword
     * @param $image
     * @return ReturnResult
     */
    public function update($userId,$mobile,$username,$oldPassword,$newPassword,$image){
        $rr=new ReturnResult();
        $user=$this->userModel->getById($userId);
        /**@var $user \Mirror\ApiBundle\Entity\User*/
        if(!$user){
            $rr->errno=Code::$user_not_exist;
            return $rr;
        }
        if($newPassword){
            if(md5($oldPassword)==$user->getPassword()){
                $user->setPassword(md5($newPassword));
            }else{
                $rr->errno=Code::$password_not_right;
                return $rr;
            }
        }
        if($mobile){
            $user->setMobile($mobile);
        }
        if($username){
            $user->setUsername($username);
        }
        if($image){
            $user->setImage($image);
            $photo=$this->fileService->getFullUrlById($image);
        }else{
            $photo='';
        }
        $this->userModel->save($user);
        $rr->result=array('photo'=>$photo);
        return $rr;
    }

    /**
     * @param $userId
     * @return ReturnResult
     */
    public function delete($userId){
        $rr=new ReturnResult();
        $user=$this->userModel->getById($userId);
        if(!$user){
            $rr->errno=Code::$user_not_exist;
            return $rr;
        }
        $this->userModel->delete($user);
        return $rr;
    }
}