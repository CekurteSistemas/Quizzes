<?php

namespace Cekurte\ZCPEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'ZCPE');
    }

    /**
     * @Route("/admin", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {
        return array();
    }
}