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
    public function listAction(){
        return array();
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
     * @OAuth()
     * @Route("/{id}/edit",requirements={"id":"\d+"})
     * @Template()
     * @param $id
     * @return array
     */
    public function editAction($id){
        return array('id'=>$id);
    }
}