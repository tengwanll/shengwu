<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/15
 * Time: 15:59
 */

namespace Mirror\ApiBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/hpv")
 * Class HpvController
 * @package Mirror\ApiBundle\Controller
 */
class HpvController extends BaseController
{
    /**
     * @Route()
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $orderNo=$request->get('orderNo','');
        $ip=$this->getIpAddress($request);
        $pageable=$this->getPage($request);
        $conn=$this->get('database_connection');
        $rr=$this->get('hpv_service')->getList($orderNo,$conn,$pageable);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getInfo($id){
        $conn=$this->get('database_connection');
        $rr=$this->get('hpv_service')->getInfo($id,$conn);
        return $this->buildResponse($rr);
    }
}