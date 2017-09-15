<?php

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/goods")
 * Class DataController
 * @package Mirror\MirrorAdmBundle\Controller
 */
class GoodsController extends Controller
{
    /**
     * @OAuth()
     * @Route("")
     * @Template
     * @return array
     */
    public function listAction(Request $request)
    {
        $role=$request->getSession()->get('role',1);
        $page=$request->get('page',1);
        return array('role'=>$role,'page'=>$page);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template
     * @return array
     */
    public function infoAction($id){
        return array('id'=>$id);
    }

    /**
     * @OAuth()
     * @Route("/add")
     * @Template
     * @return array
     */
    public function addAction(Request $request){
        $page=$request->get('page',1);
        return array('page'=>$page);
    }

    /**
     * @OAuth()
     * @Route("/edit/{id}/{page}",requirements={"id":"\d+"})
     * @Template
     * @return array
     */
    public function editAction($id,$page){
        return array('id'=>$id,'page'=>$page);
    }
}
