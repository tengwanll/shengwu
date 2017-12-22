<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/12/16
 * Time: 17:40
 */

namespace Mirror\AdmBundle\Controller;


use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/face")
 * Class FaceController
 * @package Mirror\AdmBundle\Controller
 */
class FaceController extends Controller
{
    /**
     * @Template()
     * @OAuth()
     * @Route("")
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request){
        $role=$request->getSession()->get('role',1);
        $page=$request->get('page',1);
        return array('role'=>$role,'page'=>$page);
    }

    /**
     * @Template()
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @param $page
     * @param $id
     * @return array
     */
    public function infoAction($id){
        return array('id'=>$id);
    }

    /**
     * @Route("/{id}/{page}",requirements={"\id":"\d+","page":"\d+"})
     * @param $id
     * @param $page
     * @return array
     */
    public function editAction($id,$page){
        return array('id'=>$id,'page'=>$page);
    }
}