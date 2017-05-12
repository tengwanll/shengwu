<?php

namespace Mirror\AdmBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @Route("/default")
 * Class DefaultController
 * @package Mirror\MirrorAdmBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/index")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
