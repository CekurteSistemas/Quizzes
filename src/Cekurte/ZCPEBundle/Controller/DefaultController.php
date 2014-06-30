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



        // hwi_oauth.resource_owner.google
        // hwi_oauth.security.oauth_utils
        // hwi_oauth.storage.session
        // hwi_oauth.user_checker
        // hwi_oauth.http_client

        $token = $this->get('security.context')->getToken();


        $service = $this->get('cekurte_google_api.gmail');

        // $service->getClient()->setAccessToken(
        //     $this->getUser()->getGoogleAccessToken()
        // );



        // var_dump($token, $service->getClient()->getAccessToken());exit;

        $service->getClient()->setup();

        if ($service->getClient()->isAccessTokenExpired()) {
            $service->getClient()->refreshToken(
                $token->getRefreshToken()
            );
        }

        var_dump($service->getClient()->getAccessToken(), $service->getClient());exit;

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
