<?php

namespace Cekurte\ZCPEBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cekurte\GeneratorBundle\Controller\CekurteController;
use Cekurte\GeneratorBundle\Controller\RepositoryInterface;
use Cekurte\GeneratorBundle\Office\PHPExcel as CekurtePHPExcel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cekurte\ZCPEBundle\Entity\Tag;
use Cekurte\ZCPEBundle\Entity\Repository\TagRepository;
use Cekurte\ZCPEBundle\Form\Type\TagFormType;
use Cekurte\ZCPEBundle\Form\Handler\TagFormHandler;

/**
 * Tag controller.
 *
 * @Route("/admin/tag")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class TagController extends CekurteController implements RepositoryInterface
{
    /**
     * Get a instance of TagRepository.
     *
     * @return TagRepository
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getEntityRepository()
    {
        return $this->get('doctrine')->getRepository('CekurteZCPEBundle:Tag');
    }

    /**
     * Lists all Tag entities.
     *
     * @Route("/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_tag")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_tag_paginator")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG, ROLE_USER")
     *
     * @param int $page
     * @param string $sort
     * @param string $direction
     * @return array
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function indexAction($page, $sort, $direction)
    {
        $form = $this->createForm(new TagFormType(), new Tag(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_tag')) {

            $form->bind($this->get('session')->get('search_tag'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $pagination = $this->getPagination($query, $page);

        $pagination->setUsedRoute('admin_tag_paginator');

        return array(
            'pagination'    => $pagination,
            'delete_form'   => $this->createDeleteForm()->createView(),
            'search_form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to search a Tag entity.
     *
     * @Route("/search", name="admin_tag_search")
     * @Method({"GET", "POST"})
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG, ROLE_USER")
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function searchAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $this->get('session')->set('search_tag', $request);
        } else {
            $this->get('session')->remove('search_tag');
        }

        return $this->redirect($this->generateUrl('admin_tag'));
    }

    /**
     * Export Tag entities to Excel.
     *
     * @Route("/export/sort/{sort}/direction/{direction}/", defaults={"sort"="ck.id", "direction"="asc"}, name="admin_tag_export")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG, ROLE_USER")
     *
     * @param string $sort
     * @param string $direction
     * @return StreamedResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function exportAction($sort, $direction)
    {
        $form = $this->createForm(new TagFormType(), new Tag(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_tag')) {

            $form->bind($this->get('session')->get('search_tag'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $translator = $this->get('translator');

        $office = new CekurtePHPExcel(sprintf(
            '%s %s',
            $translator->trans('Report of'),
            $translator->trans('Tag')
        ));

        $office
            ->setHeader(array(
                'question' => $translator->trans('Question'),
                'title' => $translator->trans('Title'),
                'isDeleted' => $translator->trans('Isdeleted'),
                'deletedBy' => $translator->trans('Deletedby'),
                'deletedAt' => $translator->trans('Deletedat'),
                'updatedBy' => $translator->trans('Updatedby'),
                'updatedAt' => $translator->trans('Updatedat'),
                'createdBy' => $translator->trans('Createdby'),
                'createdAt' => $translator->trans('Createdat'),
            ))
            ->setData($query->getArrayResult())
            ->build()
        ;

        return $office->createResponse();
    }

    /**
     * Creates a new Tag entity.
     *
     * @Route("/", name="admin_tag_create")
     * @Method("POST")
     * @Template("CekurteZCPEBundle:Tag:new.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_CREATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new TagFormType(), new Tag());

        $handler = new TagFormHandler(
            $form,
            $this->getRequest(),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_tag_show', array('id' => $id)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     * @Route("/new", name="admin_tag_new")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_CREATE, ROLE_ADMIN")
     *
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function newAction()
    {
        $form = $this->createForm(new TagFormType(), new Tag());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Tag entity.
     *
     * @Route("/{id}", name="admin_tag_show")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_RETRIEVE, ROLE_ADMIN")
     *
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function showAction($id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $this->createDeleteForm()->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/{id}/edit", name="admin_tag_edit")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_UPDATE, ROLE_ADMIN")
     *
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function editAction($id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $editForm = $this->createForm(new TagFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                    );
    }

    /**
     * Edits an existing Tag entity.
     *
     * @Route("/{id}", name="admin_tag_update")
     * @Method("PUT")
     * @Template("CekurteZCPEBundle:Tag:edit.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_UPDATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @param int $id
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->getEntityRepository()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $form = $this->createForm(new TagFormType(), $entity);

        $handler = new TagFormHandler(
            $form,
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_tag_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new TagFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                    );
    }

    /**
     * Deletes a Tag entity.
     *
     * @Route("/{id}", name="admin_tag_delete")
     * @Method("DELETE")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_TAG_DELETE, ROLE_ADMIN")
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function deleteAction(Request $request, $id)
    {
        $handler = new TagFormHandler(
            $this->createDeleteForm(),
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($handler->delete('CekurteZCPEBundle:Tag')) {
            return $this->redirect($this->generateUrl('admin_tag'));
        } else {
            return $this->redirect($this->generateUrl('admin_tag_show', array('id' => $id)));
        }
    }
}
