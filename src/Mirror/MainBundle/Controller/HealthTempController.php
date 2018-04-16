<?php

namespace Mirror\MainBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/server")
 * Class IndexController
 * @package Mirror\MainBundle\Controller
 */
class HealthTempController extends Controller
{
    /**
     * @Route("/{id}",requirements={"id":"\d+"})
     * @Template()
     * @return array
     */
    public function indexAction($id)
    {
        $rr=$this->get('server_service')->getIndexServer();
        return array('list'=>$rr->result['list'],'company'=>$rr->result['company'],'id'=>$id);
    }
}
