<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2017/8/28
 * Time: 14:51
 */

namespace Mirror\AdmBundle\Controller;


use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/biology")
 * Class BiologyController
 * @package Mirror\AdmBundle\Controller
 */
class BiologyController
{
    /**
     * @OAuth()
     * @Route("")
     * @return array
     * @Template
     */
    public function listAction(){
        return array();
    }

    /**
     * @OAuth()
     * @Route("/add")
     * @return array
     * @Template
     */
    public function addAction(){
        return array();
    }

    /**
     * @OAuth()
     * @Route("/{id}/edit",requirements={"id":"\d+"})
     * @return array
     * @Template
     */
    public function editAction($id){
        return array('id'=>$id);
    }

    /**
     * @OAuth()
     * @Route("/{id}",requirements={"id":"\d+"})
     * @return array
     * @Template
     */
    public function infoAction($id){
        return array('id'=>$id);
    }
}