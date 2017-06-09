<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/5/27
 * Time: 10:03
 */

namespace Mirror\AdmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/sort")
 * Class SortController
 * @package Mirror\AdmBundle\Controller
 */
class SortController extends BaseController
{
    /**
     * @Route("")
     * @Template()
     * @return array
     */
    public function listAction(){
        return array();
    }

    /**
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template()
     * @return array
     */
    public function infoAction($id){
        return array('id'=>$id);
    }

    /**
     * @Route("/{id}/add/{name}",requirements={"id":"\d+"})
     * @Template()
     * @param $id
     * @param $name
     * @return array
     */
    public function addAction($id,$name){
        return array('id'=>$id,'name'=>$name);
    }

    /**
     * @Route("/{id}/edit",requirements={"id":"\d+"})
     * @Template()
     * @param $id
     * @return array
     */
    public function editAction($id){
        return array('id'=>$id);
    }
}