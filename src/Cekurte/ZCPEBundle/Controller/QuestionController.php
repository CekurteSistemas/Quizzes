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
use Cekurte\ZCPEBundle\Entity\Question;
use Cekurte\ZCPEBundle\Entity\Repository\QuestionRepository;
use Cekurte\ZCPEBundle\Form\Type\QuestionFormType;
use Cekurte\ZCPEBundle\Form\Handler\QuestionFormHandler;

use Cekurte\ZCPEBundle\Events;
use Cekurte\ZCPEBundle\Event\QuestionAnswerEvent;
use Cekurte\ZCPEBundle\EventListener\QuestionAnswerListener;

/**
 * Question controller.
 *
 * @Route("/admin/question")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class QuestionController extends CekurteController implements RepositoryInterface
{
    /**
     * Get a instance of QuestionRepository.
     *
     * @return QuestionRepository
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getEntityRepository()
    {
        return $this->get('doctrine')->getRepository('CekurteZCPEBundle:Question');
    }

    /**
     * Lists all Question entities.
     *
     * @Route("/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_question")
     * @Route("/page/{page}/sort/{sort}/direction/{direction}/", defaults={"page"=1, "sort"="ck.id", "direction"="asc"}, name="admin_question_paginator")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION, ROLE_ADMIN")
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
        $form = $this->createForm(new QuestionFormType(), new Question(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_question')) {

            $form->bind($this->get('session')->get('search_question'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $pagination = $this->getPagination($query, $page);

        $pagination->setUsedRoute('admin_question_paginator');

        return array(
            'pagination'    => $pagination,
            'delete_form'   => $this->createDeleteForm()->createView(),
            'search_form'   => $form->createView(),
        );
    }

    /**
     * Preview mail.
     *
     * @Route("/{question}/preview-mail", name="admin_question_preview_mail")
     * @Method("GET")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_SEND_MAIL, ROLE_ADMIN")
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function previewMailAction($question)
    {
        $entity = $this->getEntityRepository()->find($question);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $listener = new QuestionAnswerListener($this->container);

        return new Response(
            $listener->getTemplateBody($entity)
        );
    }

    /**
     * Send mail.
     *
     * @Route("/{question}/send-mail", name="admin_question_send_mail")
     * @Method("GET")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_SEND_MAIL, ROLE_ADMIN")
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function sendMailAction($question)
    {
        $event = new QuestionAnswerEvent(
            $this->getEntityRepository()->find($question)
        );

        $this->get('event_dispatcher')->dispatch(
            Events::NEW_QUESTION,
            $event
        );

        return $this->redirect($this->generateUrl('admin_question_show', array(
            'id' => $question
        )));
    }

    /**
     * Displays a form to search a Question entity.
     *
     * @Route("/search", name="admin_question_search")
     * @Method({"GET", "POST"})
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION, ROLE_ADMIN")
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
            $this->get('session')->set('search_question', $request);
        } else {
            $this->get('session')->remove('search_question');
        }

        return $this->redirect($this->generateUrl('admin_question'));
    }

    /**
     * Export Question entities to Excel.
     *
     * @Route("/export/sort/{sort}/direction/{direction}/", defaults={"sort"="ck.id", "direction"="asc"}, name="admin_question_export")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION, ROLE_ADMIN")
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
        $form = $this->createForm(new QuestionFormType(), new Question(), array(
            'search' => true,
        ));

        if ($this->get('session')->has('search_question')) {

            $form->bind($this->get('session')->get('search_question'));
        }

        $query = $this->getEntityRepository()->getQuery($form->getData(), $sort, $direction);

        $translator = $this->get('translator');

        $office = new CekurtePHPExcel(sprintf(
            '%s %s',
            $translator->trans('Report of'),
            $translator->trans('Question')
        ));

        $office
            ->setHeader(array(
                'questionType' => $translator->trans('Questiontype'),
                'answer' => $translator->trans('Answer'),
                'category' => $translator->trans('Category'),
                'tag' => $translator->trans('Tag'),
                'deletedBy' => $translator->trans('Deletedby'),
                'updatedBy' => $translator->trans('Updatedby'),
                'createdBy' => $translator->trans('Createdby'),
                'googleGroupsId' => $translator->trans('Googlegroupsid'),
                'title' => $translator->trans('Title'),
                'difficulty' => $translator->trans('Difficulty'),
                'comment' => $translator->trans('Comment'),
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
     * Creates a new Question entity.
     *
     * @Route("/", name="admin_question_create")
     * @Method("POST")
     * @Template("CekurteZCPEBundle:Question:new.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_CREATE, ROLE_ADMIN")
     *
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new QuestionFormType(), new Question());

        $handler = new QuestionFormHandler(
            $form,
            $this->getRequest(),
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $id)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/new", name="admin_question_new")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_CREATE, ROLE_ADMIN")
     *
     * @return array|Response
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function newAction()
    {
        $form = $this->createForm(new QuestionFormType(), new Question());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/{id}", name="admin_question_show")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_RETRIEVE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $this->createDeleteForm()->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{id}/edit", name="admin_question_edit")
     * @Method("GET")
     * @Template()
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_UPDATE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                                                                                            );
    }

    /**
     * Edits an existing Question entity.
     *
     * @Route("/{id}", name="admin_question_update")
     * @Method("PUT")
     * @Template("CekurteZCPEBundle:Question:edit.html.twig")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_UPDATE, ROLE_ADMIN")
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
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $form = $this->createForm(new QuestionFormType(), $entity);

        $handler = new QuestionFormHandler(
            $form,
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($id = $handler->save()) {
            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $id)));
        }

        $editForm = $this->createForm(new QuestionFormType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $this->createDeleteForm()->createView(),                                                                                                                                                                                            );
    }

    /**
     * Deletes a Question entity.
     *
     * @Route("/{id}", name="admin_question_delete")
     * @Method("DELETE")
     * @Secure(roles="ROLE_CEKURTEZCPEBUNDLE_QUESTION_DELETE, ROLE_ADMIN")
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
        $handler = new QuestionFormHandler(
            $this->createDeleteForm(),
            $request,
            $this->get('doctrine')->getManager(),
            $this->get('session')->getFlashBag(),
            $this->getUser()
        );

        if ($handler->delete('CekurteZCPEBundle:Question')) {
            return $this->redirect($this->generateUrl('admin_question'));
        } else {
            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $id)));
        }
    }
}
