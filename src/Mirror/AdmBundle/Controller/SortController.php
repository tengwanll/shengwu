<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/27
 * Time: 10:03
 */

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/sort")
 * Class SortController
 * @package Mirror\AdmBundle\Controller
 */
class SortController extends BaseController
{
    /**
     * @OAuth()
     * @Route("")
     * @Template()
     * @return array
     */
    public function listAction(Request $request){
        $sortId=$request->get('sortId',1);
        return array('sortId'=>$sortId);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template()
     * @return array
     */
    public function infoAction($id){
        return array('id'=>$id);
    }

    /**
     * @OAuth()
     * @Route("/{id}/add/{sortId}",requirements={"id":"\d+"})
     * @Template()
     * @param $id
     * @param $name
     * @return array
     */
    public function addAction($id,$sortId){
        return array('id'=>$id,'sortId'=>$sortId);
    }

    /**
     * @OAuth()
     * @Route("/{id}/edit/{sortId}",requirements={"id":"\d+"})
     * @Template()
     * @param $id
     * @return array
     */
    public function editAction($id,$sortId){
        return array('id'=>$id,'sortId'=>$sortId);
    }
}