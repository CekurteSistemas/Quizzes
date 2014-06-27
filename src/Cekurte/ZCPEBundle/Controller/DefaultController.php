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
     * @Route("/admin/", name="admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAdminAction()
    {

        // $service->getClient()->setAccessToken(
        //     $this->getUser()->getGoogleAccessToken()
        // );

        // hwi_oauth.resource_owner.google
        // hwi_oauth.security.oauth_utils
        // hwi_oauth.storage.session
        // hwi_oauth.user_checker
        // hwi_oauth.http_client

        $service = $this->get('cekurte_google_api.gmail');

        if($service->getClient()->isAccessTokenExpired()) {

            $service->getClient()->setScopes(array(
                'https://www.googleapis.com/auth/gmail.compose'
            ));


            $token = $this->get('security.context')->getToken();

            // $rawToken = $token->getRawToken();

            // $customToken = array(
            //     // 'access_token'      => $token->getAccessToken(),
            //     'refresh_token'     => $token->getRefreshToken(),
            //     // 'id_token'          => $rawToken['id_token'],
            //     // 'token_type'        => $rawToken['token_type'],
            //     // 'expires_in'        => $token->getExpiresIn(),
            //     // 'created'           => $token->getCreatedAt(),
            // );

            $service->getClient()->refreshToken(json_encode(array(
                'refresh_token' => $token->getRefreshToken(),
                'grant_type'    => 'refresh_token',
            )));

            var_dump($service->getClient());
}



die('sucess');
        $service = new \Google_Service_Analytics($service->getClient());

        // request user accounts
        $accounts = $service->management_accountSummaries->listManagementAccountSummaries();

       foreach ($accounts->getItems() as $item) {
        echo "Account: ",$item['name'], "  " , $item['id'], "<br /> \n";
        foreach($item->getWebProperties() as $wp) {
            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WebProperty: ' ,$wp['name'], "  " , $wp['id'], "<br /> \n";

            $views = $wp->getProfiles();
            if (!is_null($views)) {
                foreach($wp->getProfiles() as $view) {
                //  echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;View: ' ,$view['name'], "  " , $view['id'], "<br /> \n";
                }
            }
        }
    } // closes account summaries










        // var_dump(get_class_methods($token), $token, $token->getRawToken());exit;

        // public 'access_token' => string 'TOKEN' (length=5)
        // public 'refresh_token' => string 'TOKEN' (length=5)
        // public 'token_type' => string 'Bearer' (length=6)
        // public 'expires_in' => int 3600
        // public 'id_token' => string 'TOKEN' (length=5)
        // public 'created' => int 1320790426






        //  var_dump($accessToken);exit;

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
