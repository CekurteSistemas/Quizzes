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
use Cekurte\ZCPEBundle\Entity\Category;
use Cekurte\ZCPEBundle\Entity\Repository\CategoryRepository;
use Cekurte\ZCPEBundle\Form\Type\CategoryFormType;
use Cekurte\ZCPEBundle\Form\Handler\CategoryFormHandler;

/**
 * Category controller.
 *
 * @Route("/admin/category")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class CategoryController extends CekurteController implements RepositoryInterface
{
    /**
     * Get a instance of CategoryRepository.
     *
     * @return CategoryRepository
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getEntityRepository()
    {
        return $this->get('doctrine')->getRepository('CekurteZCPEBundle:Category');
    }

    /**
     * Lists all Category entities.
     *
     * @Route("/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_category")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_category_paginator")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY, ROLE_ADMIN")
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
        $form = $this->createForm(new CategoryFormType(), new Category(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_category')) {

            $form->bind($this->get('session')->get('search_category'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $pagination = $this->getPagination($query, $page);

        $pagination->setUsedRoute('admin_category_paginator');

        return array(
            'pagination'    => $pagination,
            'delete_form'   => $this->createDeleteForm()->createView(),
            'search_form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to search a Category entity.
     *
     * @Route("/search", name="admin_category_search")
     * @Method({"GET", "POST"})
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY, ROLE_ADMIN")
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
            $this->get('session')->set('search_category', $request);
        } else {
            $this->get('session')->remove('search_category');
        }

        return $this->redirect($this->generateUrl('admin_category'));
    }

    /**
     * Export Category entities to Excel.
     *
     * @Route("/export/sort/{sort}/direction/{direction}/", defaults={"sort"="ck.id", "direction"="asc"}, name="admin_category_export")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY, ROLE_ADMIN")
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
        $form = $this->createForm(new CategoryFormType(), new Category(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_category')) {

            $form->bind($this->get('session')->get('search_category'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $translator = $this->get('translator');

        $office = new CekurtePHPExcel(sprintf(
            '%s %s',
            $translator->trans('Report of'),
            $translator->trans('Category')
        ));

        $office
            ->setHeader(array(
                'question' => $translator->trans('Question'),
                'deletedBy' => $translator->trans('Deletedby'),
                'updatedBy' => $translator->trans('Updatedby'),
                'createdBy' => $translator->trans('Createdby'),
                'title' => $translator->trans('Title'),
                'description' => $translator->trans('Description'),
                'deleted' => $translator->trans('Deleted'),
                'deletedAt' => $translator->trans('Deletedat'),
                'updatedAt' => $translator->trans('Updatedat'),
                'createdAt' => $translator->trans('Createdat'),
            ))
            ->setData($query->getArrayResult())
            ->build()
        ;

        return $office->createResponse();
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/", name="admin_category_create")
     * @Method("POST")
     * @Template("CekurteZCPEBundle:Category:new.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_CREATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new CategoryFormType(), new Category());

        $handler = new CategoryFormHandler(
            $form,
            $this->getRequest(),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_category_show', array('id' => $id)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_CREATE, ROLE_ADMIN")
     *
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function newAction()
    {
        $form = $this->createForm(new CategoryFormType(), new Category());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", name="admin_category_show")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_RETRIEVE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $this->createDeleteForm()->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="admin_category_edit")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_UPDATE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createForm(new CategoryFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                                );
    }

    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}", name="admin_category_update")
     * @Method("PUT")
     * @Template("CekurteZCPEBundle:Category:edit.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_UPDATE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $form = $this->createForm(new CategoryFormType(), $entity);

        $handler = new CategoryFormHandler(
            $form,
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_category_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new CategoryFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                                );
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", name="admin_category_delete")
     * @Method("DELETE")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_CATEGORY_DELETE, ROLE_ADMIN")
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
        $handler = new CategoryFormHandler(
            $this->createDeleteForm(),
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($handler->delete('CekurteZCPEBundle:Category')) {
            return $this->redirect($this->generateUrl('admin_category'));
        } else {
            return $this->redirect($this->generateUrl('admin_category_show', array('id' => $id)));
        }
    }
}
