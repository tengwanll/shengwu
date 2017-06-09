<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/6/1
 * Time: 14:01
 */

namespace Mirror\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/car")
 * Class CarController
 * @package Mirror\ApiBundle\Controller
 */
class CarController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $userId=$this->sessionGet($request,'userId',0);
        $pageable=$this->getPage($request);
        $rr=$this->get('car_service')->getList($pageable,$userId);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/number/{id}",requirements={"id":"\d+"})
     * @Method("POST")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addNumber($id){
        $rr=$this->get('car_service')->addNumber($id);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/number/{id}",requirements={"id":"\d+"})
     * @Method("DELETE")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function subNumber($id){
        $rr=$this->get('car_service')->subNumber($id);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("")
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCar(Request $request){
        $json=$this->getJson($request);
        $carId=$json->get('carId',0);
        $rr=$this->get('car_service')->deleteCar($carId);
        return $this->buildResponse($rr);
    }

}