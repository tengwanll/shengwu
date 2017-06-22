<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/15
 * Time: 17:16
 */

namespace Mirror\ApiBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/goods")
 * Class GoodsController
 * @package Mirror\ApiBundle\Controller
 */
class GoodsController extends  BaseController
{
    /**
     * @OAuth()
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request){
        $pageable=$this->getPage($request);
        $name=$request->get('name','');
        $sort=$request->get('sort',null);
        $bigPrice=$request->get('bigPrice',null);
        $smallPrice=$request->get('smallPrice',null);
        $rr=$this->get('goods_service')->getList($name,$sort,$bigPrice,$smallPrice,$pageable);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDetail($id){
        $conn=$this->get('database_connection');
        $rr=$this->get('goods_service')->getDetail($id,$conn);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/car")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addToCar(Request $request){
        $json=$this->getJson($request);
        $goodsId=$json->get('id',0);
        $userId=$this->sessionGet($request,'userId',0);
        $rr=$this->get('goods_service')->addToCar($goodsId,$userId);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addGoods(Request $request){
        $goods=$this->serializerByJson($request,'Goods');
        $conn=$this->get('database_connection');
        $rr=$this->get('goods_service')->create($goods,$conn);
        return $this->buildResponse($rr);
    }
}