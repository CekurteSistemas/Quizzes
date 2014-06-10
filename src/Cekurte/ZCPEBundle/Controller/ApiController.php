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
        $categoryRepository = $this->get('doctrine')->getRepository('CekurteZCPEBundle:Category');

        $categoryFilter = new Category();
        $categoryFilter->setDeleted(false);

        $result = $categoryRepository
            ->getQuery($categoryFilter, 'ck.title', 'asc')
            ->getResult()
        ;

        $data = array();

        foreach ($result as $item) {
            $data[] = array(
                'id'        => $item->getId(),
                'category'  => $item->getTitle(),
            );
        }

        return new JsonResponse($data);
    }
}
