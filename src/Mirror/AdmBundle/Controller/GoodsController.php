<?php

namespace Mirror\AdmBundle\Controller;

use Mirror\ApiBundle\Annotation\OAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    public function listAction()
    {
        return array();
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
}
