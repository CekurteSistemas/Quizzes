<?php

namespace Cekurte\Custom\UserBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Cekurte\UserBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManager;

class FOSUBUserProvider extends BaseClass
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Gets the value of entityManager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Sets the value of entityManager.
     *
     * @param EntityManager $entityManager the entity manager
     *
     * @return FOSUBUserProvider
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = parent::loadUserByOAuthUserResponse($response);

        $groupContributor = $this->getEntityManager()->getRepository('CekurteCustomUserBundle:Group')->findOneBy(array(
            'name' => 'Contributor'
        ));

        $responseGoogle = $response->getResponse();

        $user
            ->setGender($responseGoogle['gender'])
            ->addGroup($groupContributor)
        ;

        return $user;
    }
}
