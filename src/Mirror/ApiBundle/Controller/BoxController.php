<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/16
 * Time: 18:03
 */

namespace Mirror\ApiBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/box")
 * Class BoxController
 * @package Mirror\ApiBundle\Controller
 */
class BoxController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $uniqueId=$request->get('uniqueId','');
        $pageable=$this->getPage($request);
        $conn=$this->get('database_connection');
        $rr=$this->get('box_service')->getList($uniqueId,$conn,$pageable);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request){
        $json=$this->getJson($request);
        $num=$json->get('num',1);
        $conn=$this->get('database_connection');
        $rr=$this->get('box_service')->add($num,$conn);
        return $this->buildResponse($rr);
    }
}