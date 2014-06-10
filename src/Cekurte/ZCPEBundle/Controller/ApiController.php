<?php

namespace Cekurte\ZCPEBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cekurte\GeneratorBundle\Controller\CekurteController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cekurte\ZCPEBundle\Entity\Category;
use Cekurte\ZCPEBundle\Entity\Question;

/**
 * Api controller.
 *
 * @Route("/api")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class ApiController extends CekurteController
{
    /**
     * Lists all Category entities.
     *
     * @Route("/categories")
     * @Method("GET")
     *
     * @return JsonResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function categoriesAction()
    {
        $repository = $this->get('doctrine')->getRepository('CekurteZCPEBundle:Category');

        $entityFilter = new Category();
        $entityFilter->setDeleted(false);

        $result = $repository
            ->getQuery($entityFilter, 'ck.title', 'asc')
            ->getResult()
        ;

        $data = array();

        foreach ($result as $item) {
            $data[] = array(
                'id'            => $item->getId(),
                'category'      => $item->getTitle(),
                'description'   => $item->getDescription(),
            );
        }

        return new JsonResponse($data);
    }

    /**
     * Lists all Question entities.
     *
     * @Route("/questions")
     * @Method("GET")
     *
     * @return JsonResponse
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function questionsAction()
    {
        $repository = $this->get('doctrine')->getRepository('CekurteZCPEBundle:Question');

        $entityFilter = new Question();
        $entityFilter->setDeleted(false);

        $result = $repository
            ->getQuery($entityFilter, 'ck.id', 'asc')
            ->getResult()
        ;

        $data = array();

        foreach ($result as $item) {

            $dataItem = array(
                'id'                => $item->getId(),
                'google_groups_id'  => $item->getGoogleGroupsId(),
                'question'          => $item->getTitle(),
                'question_type'     => array(
                    'id'            => $item->getQuestionType()->getId(),
                    'type'          => $item->getQuestionType()->getTitle(),
                ),
                'difficulty'        => $item->getDifficulty(),
                'comment'           => $item->getComment(),
                'created_at'        => $item->getCreatedAt(),
                'created_by'        => $item->getCreatedBy()->getName(),
            );

            foreach ($item->getCategory() as $row) {
                $dataItem['categories'][] = $row->getId();
            }

            foreach ($item->getQuestionHasAnswer() as $row) {
                $dataItem['alternatives'][] = array(
                    'id'            => $row->getAnswer()->getId(),
                    'is_correct'    => $row->isCorrect(),
                    'alternative'   => $row->getAnswer()->getTitle(),
                );
            }

            $data[] = $dataItem;
        }

        return new JsonResponse($data);
    }
}
