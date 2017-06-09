<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2016/8/5
 * Time: 14:10
 */

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\AAuth;
use Mirror\ApiBundle\Annotation\OAuth;
use Mirror\ApiBundle\Annotation\PAuth;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\Common\Constant;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/user");
 * Class UserController
 * @package Mirror\ApiBundle\Controller
 */
class UserController extends BaseController
{
    /**
     * @Route("/login")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login(Request $request){
        $json=$this->getJson($request);
        $telephone=$json->get('telephone','');
        $password=$json->get('password','');
        $ip=$this->getIpAddress($request);
        $rr=$this->get('user_service')->login($telephone,$password,$ip);
        if($rr->errno==0){
            $this->sessionSet($request,'userId',$rr->result['userId']);
            $this->sessionSet($request,'username',$rr->result['username']);
            $this->sessionSet($request,'telephone',$rr->result['telephone']);
            $this->sessionSet($request,Constant::$login_entity,1);
        }
        return $this->buildResponse($rr);
    }

    /**
     * 注销
     * @OAuth()
     * @Route("/logout")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logout(Request $request){
        $this->sessionSet($request,'userId',null);
        $this->sessionSet($request,Constant::$login_entity,null);
        $this->sessionSet($request,'identification',null);
        $this->sessionSet($request,'telephone',null);
        return $this->buildResponse(new ReturnResult());
    }

    /**
     * 注册
     * @Route("")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request){
        $user=$this->serializerByJson($request,'User');
        $json=$this->getJson($request);
        $code=$json->get('code','');
        if($this->get('telephone_code_service')->validateCode($user->getTelephone(),$code)){
            $this->get('telephone_code_service')->completeValid($user->getTelephone(), $code);
        }else{
            return $this->buildResponse(new ReturnResult(Code::$code_error));
        }
        /**@var $user \Mirror\ApiBundle\Entity\User*/
        $rr=$this->get('user_service')->create($user);
        if($rr->errno==0){
            $this->sessionSet($request,'userId',$rr->result['userId']);
            $this->sessionSet($request,'telephone',$rr->result['telephone']);
            $this->sessionSet($request,'identification',$rr->result['identification']);
            $this->sessionSet($request,Constant::$login_entity,1);
        }
        return $this->buildResponse($rr);
    }

    /**
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function userList(Request $request){
        $pageable=$this->getPage($request);
        $username=$request->get('username','');
        $mobile=$request->get('mobile','');
        $rr=$this->get('user_service')->getList($pageable,$username,$mobile);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function detail($id){
        $rr=$this->get('user_service')->detail($id);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/order")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function userOrder(Request $request){
        $pageable=$this->getPage($request);
        $userId=$request->get('userId',0);
        $rr=$this->get('user_service')->getUserOrder($pageable,$userId);
        return $this->buildResponse($rr);
    }

    /**
     * 判断用户是否注册
     * @Route("/checktelephone")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkTelephone(Request $request){
        $json=$this->getJson($request);
        $telephone=$json->get('telephone','');
        $rr=$this->get('user_service')->checkTelephone($telephone);
        return $this->buildResponse($rr);
    }

    /**
     * 判断验证码是否正确
     * @Route("/checkcode")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkCode(Request $request){
        $json=$this->getJson($request);
        $code=$json->get('code','');
        $telephone=$json->get('telephone','');
        if($this->get('telephone_code_service')->validateCode($telephone,$code)){
            $this->get('telephone_code_service')->completeValid($telephone, $code);
        }else{
            return $this->buildResponse(new ReturnResult(Code::$code_error));
        }
        $rr=new ReturnResult();
        return $this->buildResponse($rr);
    }



    /**
     * @Route("/test")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function testAction(Request $request){
        return $this->buildResponse(new ReturnResult());
    }
}