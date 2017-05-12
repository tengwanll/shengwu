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
use Mirror\ApiBundle\Entity\Orders;
use Mirror\ApiBundle\Model\LogLoginModel;
use Mirror\ApiBundle\Model\FileModel;
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
    private $fileModel;
    private $telephoneCodeService;
    private $ordersModel;

    /**
     * @InjectParams({
     *      "userModel" = @Inject("user_model"),
     *     "logLoginModel"=@Inject("log_login_model"),
     *     "fileModel"=@Inject("file_model"),
     *     "orderModel"=@Inject("orders_model")
     * })
     * UserService constructor.
     * @param UserModel $userModel
     * @param LogLoginModel $logLoginModel
     * @param FileModel $fileModel
     * @param TelephoneCodeService $telephoneCodeService
     * @param OrdersModel $ordersModel
     */
    public function __construct(UserModel $userModel,LogLoginModel $logLoginModel,TelephoneCodeService $telephoneCodeService,FileModel $fileModel,OrdersModel $ordersModel)
    {
        $this->userModel=$userModel;
        $this->logLoginModel=$logLoginModel;
        $this->telephoneCodeService=$telephoneCodeService;
        $this->fileModel=$fileModel;
        $this->ordersModel=$ordersModel;
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
    public function login($telephone,$password,$ipAddress,$creditId=0,$propertyId=0,$rewardId=0){
        $rr=new ReturnResult();
//        $user=$this->userModel->getOneByCriteria(array('telephone'=>$telephone,'status'=>1));
//        if(!$user){
//            $rr->errno=Code::$user_not_exist;
//            return $rr;
//        }
//        if($user->getStatus()==2){
//            $rr->errno=Code::$user_forbidden;
//            return $rr;
//        }
//        //判断密码
//        if(md5($password)!=$user->getPassword()){
//            $rr->errno=Code::$password_not_right;
//            return $rr;
//        }
//        //验证是否有携带债权
//        if($creditId){
//            $credit=$this->creditModel->getById($creditId);
//            $credit->setUserId($user->getId());
//            $this->creditModel->flush($credit);
//        }
//        //验证是否携带资产
//        if($propertyId){
//            $property=$this->propertyModel->getById($propertyId);
//            $property->setUserId($user->getId());
//            $this->propertyModel->flush($property);
//        }
//        //验证是否携带悬赏
//        if($rewardId){
//            $reward=$this->rewardModel->getById($rewardId);
//            if($reward){
//                $reward->setUserId($user->getId());
//                $this->propertyModel->flush($reward);
//            }
//        }
//        //生成日志
//        $this->logLoginModel->saveLogin($user->getId(),Constant::$login_type_user,$ipAddress);
//        $data=array(
//            'userId'=>$user->getId(),
//            'telephone'=>$user->getTelephone(),
//            'identification'=>$user->getIdentification()
//        );
//        $rr->result=$data;
        return $rr;
    }

   public function getList($pageable,$username,$mobile){
       $rr=new ReturnResult();
       $arguments=array('status'=>1);
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
           $image=$this->fileModel->getById($imageId);
           $userId=$user->getId();
           $pageable=new Pageable(1,1);
           $userLoginLog=$this->logLoginModel->getByPages(array('entityId'=>$userId),$pageable,'createTime desc');
            $arr[]=array(
                'id'=>$userId,
                'username'=>$user->getUsername(),
                'mobile'=>$user->getMobile(),
                'image'=>$image?$image->getUrl():'',
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
        $image=$this->fileModel->getById($imageId);
        $arr=array(
            'userId'=>$user->getId(),
            'username'=>$user->getUsername(),
            'mobile'=>$user->getMobile(),
            'image'=>$image?$image->getUrl():'',
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
            'status'=>1
        );
        $list=$this->ordersModel->getByParams($argument,$pageable);
        $arr=array();
        foreach($list->getIterator() as $order){
            /**@var $order \Mirror\ApiBundle\Entity\Orders*/
            $arr[]=array(
                'id'=>$order->getId(),
                'number'=>$order->getNumber(),
                'createTime'=>$order->getCreateTime()->format('Y-m-d H:i:s'),
                'price'=>$order->getPrice(),
                'status'=>$order->getStatus()
            );
        }
        $rr->result=array('list'=>$arr,'total'=>$list->count());
        return $rr;
    }
}