<?php

namespace Cekurte\ZCPEBundle\Controller;

use Cekurte\ZCPEBundle\Entity\Question;
use Cekurte\ZCPEBundle\Form\Type\QuestionAnonymousFormType;
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
        $form = $this->createForm(new QuestionAnonymousFormType(), new Question());

        return array(
            'form'              => $form->createView(),
            'subject_template'  => sprintf('%s: ', $this->container->getParameter('cekurte_zcpe_google_group_subject')),
        );
    }

    /**
     * @Route("/admin/", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {
        return array();
    }

    /**
     * @Route("/admin/login", name="admin_login")
     * @Method("GET")
     * @Template()
     */
    public function loginAdminAction()
    {
        return array();
    }
}
