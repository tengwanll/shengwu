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
 * @Route("/sort")
 * Class SortController
 * @package Mirror\ApiBundle\Controller
 */
class ServerSortController extends BaseController
{
    /**
     * @OAuth()
     * @Route("")
     * @Method("GET")
     * @param Request $request
     * @return mixed
     */
    public function getList(Request $request){
        $rr=$this->get('sort_service')->getList();
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("")
     * @Method("POST")
     * @param Request $request
     * @return mixed
     */
    public function addSort(Request $request){
        $json=$this->getJson($request);
        $name=$json->get('name','');
        $parentId=$json->get('parentId',0);
        $description=$json->get('description',0);
        $logo=$json->get('logo','');
        $rr=$this->get('server_sort_service')->add($name,$parentId,$logo,$description);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function detail($id){
        $rr=$this->get('sort_service')->detail($id);
        return $this->buildResponse($rr);
    }

    /**
     * @OAuth()
     * @Route("")
     * @Method("PUT")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function edit(Request $request){
        $json=$this->getJson($request);
        $name=$json->get('name','');
        $id=$json->get('id',0);
        $image=$json->get('image','');
        $attr=$json->get('attr','');
        $rr=$this->get('sort_service')->edit($name,$id,$image,$attr);
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