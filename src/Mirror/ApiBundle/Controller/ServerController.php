<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/27
 * Time: 10:44
 */

namespace Mirror\ApiBundle\Controller;


use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/server")
 * Class SortController
 * @package Mirror\ApiBundle\Controller
 */
class ServerController extends BaseController
{
    /**
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request){
        $rr=$this->get('server_service')->getIndexServer();
        return $this->buildResponse($rr);
    }

    /**
     * 获取中间页分类的服务
     * @Route("/{sortId}",requirements={"sortId":"\d+"})
     * @Method("GET")
     * @param $sortId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSortList($sortId){
        $rr=$this->get('server_service')->getListBySort($sortId);
        return $this->buildResponse($rr);
    }

    /**
     * 获取服务详情
     * @Route("/info/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function detail($id){
        $rr=$this->get('server_service')->detail($id);
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
        $data=$json->getAll();
        $rr=$this->get('server_service')->add($data);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/leaf")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getLeaf(){
        $rr=$this->get('sort_service')->getLeaf();
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("")
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(Request $request){
        $json=$this->getJson($request);
        $id=$json->get('sortId',0);
        $rr=$this->get('sort_service')->delete($id);
        return $this->buildResponse($rr);
    }

    /**
     * @Route("/list/{sortId}",requirements={"sortId":"\d+"})
     * @Method("GET")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDetailList(Request $request,$sortId){
        $rr=$this->get('sort_service')->getDetailList($sortId);
        return $this->buildResponse($rr);
    }
}