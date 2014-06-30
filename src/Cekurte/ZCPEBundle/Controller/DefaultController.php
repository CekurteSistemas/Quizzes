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

        try {

            $raw =
                "To: jpcercal@gmail.com\r\n" .
                "From: sistemas@cekurte.com\r\n" .
                "Subject: Assunto novo\r\n" .
                "Content-Type: text/html; charset=utf-8\r\n" .
                "Content-Transfer-Encoding: quoted-printable\r\n\n" .

                "Olá <b>Gmail</b>!"
            ;

            $message = new \Google_Service_Gmail_Message();

            $message->setRaw(base64_encode($raw));

            $service->users_messages->send('me', $message);

        } catch (\Google_Service_Exception $e) {
            var_dump($e);
            exit;
        }

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
