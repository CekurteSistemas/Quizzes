<?php

namespace Cekurte\Custom\UserBundle\Security\Core\User;

use Cekurte\UserBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * {@inheritDoc}
 */
class FOSUBUserProvider extends BaseClass
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;



    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        return parent::connect($user, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $this->getSession()->set('google_code', $_GET['code']);

        return parent::loadUserByOAuthUserResponse($response);
    }

    /**
     * Gets the value of session.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Sets the value of session.
     *
     * @param \Symfony\Component\HttpFoundation\Session\Session $session the session
     *
     * @return FOSUBUserProvider
     */
    public function setSession(\Symfony\Component\HttpFoundation\Session\Session $session)
    {
        $this->session = $session;

        return $this;
    }
}