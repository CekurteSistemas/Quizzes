<?php

namespace Cekurte\ZCPEBundle\Form\Handler;

use Cekurte\GeneratorBundle\Form\Handler\CekurteFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use FOS\UserBundle\Model\UserInterface;

/**
 * Custom handler.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class CustomFormHandler extends CekurteFormHandler
{
    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * CustomFormHandler
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param EntityManager $em
     * @param FlashBag      $flashBag
     * @param UserInterface $user
     */
    public function __construct(FormInterface $form, Request $request, EntityManager $em, FlashBag $flashBag, UserInterface $user)
    {
        parent::__construct($form, $request, $em, $flashBag);

        $this->setUser($user);
    }

    /**
     * Get user
     *
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets user
     *
     * @param UserInterface $user
     *
     * @return CustomFormHandler
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inherited}
     */
    public function save()
    {
        if ($this->formIsValid()) {

            $data = $this->getForm()->getData();

            $id = $data->getId();

            if (empty($id)) {
                $this->getForm()->getData()
                    ->setDeleted(false)
                    ->setCreatedBy($this->getUser())
                    ->setCreatedAt(new \DateTime('NOW'))
                ;
            } else {
                $this->getForm()->getData()
                    ->setUpdatedBy($this->getUser())
                    ->setUpdatedAt(new \DateTime('NOW'))
                ;
            }

            $this->getFlashBag()->add('message', array(
                'type'      => 'success',
                'message'   => sprintf('The record has been %s successfully.', $data->getId() ? 'updated ' : 'created'),
            ));

            $this->getManager()->persist($data);
            $this->getManager()->flush();

            return $data->getId();
        }

        return false;
    }

    /**
     * Remove um registro do banco de dados.
     *
     * @param string $entityName BundleName:Entity
     * @param string $fieldName default: id
     *
     * @return boolean True se remover o registro, false do contrário
     */
    public function delete($entityName, $fieldName = 'id')
    {
        if ($this->formIsValid()) {

            $entity = $this->getManager()->getRepository($entityName)->findOneBy(array(
                $fieldName => $this->getRequest()->request->get($fieldName)
            ));

            if (!$entity) {

                $this->getFlashBag()->add('message', array(
                    'type'      => 'error',
                    'message'   => 'The record was not found.',
                ));

                return false;
            }

            $entity
                ->setDeleted(true)
                ->setDeletedBy($this->getUser())
                ->setDeletedAt(new \DateTime('NOW'))
            ;

            $this->getManager()->persist($entity);
            $this->getManager()->flush();

            $this->getFlashBag()->add('message', array(
                'type'      => 'success',
                'message'   => 'The record has been removed successfully.',
            ));

            return true;
        }

        return false;
    }
}
