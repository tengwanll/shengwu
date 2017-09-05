<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/8/25
 * Time: 11:42
 */

namespace Mirror\ApiBundle\Controller;


use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/biology")
 * Class BiologyController
 * @package Mirror\ApiBundle\Controller
 */
class BiologyController extends BaseController
{
    /**
     * @OAuth()
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getList(Request $request){
        $pageable=$this->getPage($request);
        $name=$request->get('name','');
        $rr=$this->get('biology_service')->getList($name,$pageable);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route()
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Request $request){
        $json=$this->getJson($request);
        $id=$json->get('id','');
        $rr=$this->get('biology_service')->delete($id);
        return $this->buildResponse($rr);
    }
}