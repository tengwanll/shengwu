<?php

namespace Mirror\MainBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @DI\Service("/index")
 * Class IndexController
 * @package Mirror\MainBundle\Controller
 */
class IndexController extends Controller
{
    /**
     * @Route("")
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}
