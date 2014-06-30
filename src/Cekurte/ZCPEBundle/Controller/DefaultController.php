<?php

namespace Cekurte\ZCPEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use GuzzleHttp\Client;

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
     * @Route("/admin/", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {
        $token = $this->get('security.context')->getToken();

        $service = $this->get('cekurte_google_api.gmail');

        if ($service->getClient()->isAccessTokenExpired()) {
            $service->getClient()->refreshToken(
                $token->getRefreshToken()
            );
        }

        $messages = $service->users_messages->listUsersMessages('me');

        foreach ($messages->getMessages() as $message) {

            $gmailMessage = $service->users_messages->get('me', $message->id);

            var_dump($gmailMessage->snippet);
            exit;
        }

        exit;

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
