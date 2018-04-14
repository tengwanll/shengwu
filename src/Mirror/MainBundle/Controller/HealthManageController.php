<?php

namespace Mirror\MainBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/server)
 * Class IndexController
 * @package Mirror\MainBundle\Controller
 */
class HealthManageController extends Controller
{
    /**
     * @Route("/sort/{id}",requirements={"id":"\d+"}")
     * @Template()
     * @return array
     */
    public function indexAction($id)
    {
        return array();
    }
}
