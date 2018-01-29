<?php
/**
 * Created by PhpStorm.
 * User: 31726
 * Date: 2018/1/26
 * Time: 16:59
 */

namespace Mirror\MainBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/contact")
 * Class ContactController
 * @package Mirror\MainBundle\Controller
 */
class ContactController extends Controller
{
    /**
     * @Route()
     * @Template()
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
}