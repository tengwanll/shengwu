<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/5
 * Time: 11:34
 */

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Mirror\ApiBundle\Common\Code;
use Mirror\ApiBundle\ViewModel\ReturnResult;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/order")
 * Class OrderController
 * @package Mirror\ApiBundle\Controller
 */
class OrderController extends BaseController
{
    /**
     * @OAuth()
     * @Route()
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $pageable=$this->getPage($request);
        $number=$request->get('number','');
        $beginTime=$request->get('beginTime','');
        $endTime=$request->get('endTime','');
        $username=$request->get('username','');
        $status=$request->get('status','all');
        $rr=$this->get('order_service')->getList($pageable,$number,$beginTime,$endTime,$username,$status);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getInfo($id){
        $rr=$this->get('order_service')->getInfo($id);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/{id}/goods",requirements={"id":"\d+"})
     * @Method("GET")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getOrderGoods($id,Request $request){
        $pageable=$this->getPage($request);
        $rr=$this->get('order_service')->getOrderGoods($pageable,$id);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request){
        $json=$this->getJson($request);
        $carId=$json->get('carId',array());
        $price=$json->get('price',0);
        $userId=$this->sessionGet($request,'userId',0);
        $message=$json->get('message','');
        $rr=$this->get('order_service')->create($carId,$price,$userId,$message);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/manage")
     * @Method("PUT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changeStatus(Request $request){
        $role=$this->sessionGet($request,'role',1);
        if($role<1){
            $rr=new ReturnResult();
            $rr->errno=Code::$permission_reject;
            return $this->buildResponse($rr);
        }
        $json=$this->getJson($request);
        $orderId=$json->get('orderId',0);
        $status=$json->get('status',1);
        $message=$json->get('message','');
        $rr=$this->get('order_service')->changeStatus($orderId,$status,$message);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/price")
     * @Method("PUT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changePrice(Request $request){
        $role=$this->sessionGet($request,'role',1);
        if($role<1){
            $rr=new ReturnResult();
            $rr->errno=Code::$permission_reject;
            return $this->buildResponse($rr);
        }
        $json=$this->getJson($request);
        $orderId=$json->get('id',0);
        $price=$json->get('price',1);
        $rr=$this->get('order_service')->changePrice($orderId,$price);
        return $this->buildResponse($rr);
    }
}