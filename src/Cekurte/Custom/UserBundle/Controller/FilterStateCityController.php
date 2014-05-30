<?php

namespace Cekurte\Custom\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cekurte\GeneratorBundle\Controller\CekurteController;
use Cekurte\GeneratorBundle\Controller\RepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cekurte\Custom\UserBundle\Entity\City;

/**
 * Filter State by City Controller.
 *
 * @Route("/")
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class FilterStateCityController extends CekurteController implements RepositoryInterface
{
    /**
     * Get a instance of Entity.
     *
     * @return \Cekurte\Custom\UserBundle\Entity\City
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getEntityRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('CekurteCustomUserBundle:City');
    }

    /**
     * @Route("/state/city/default-label", name="cekurte_custom_user_state_city_default_label", options={"expose"=true}))
     * @Method("GET")
     * @Template()
     */
    public function getCityDefaultLabelValueAction()
    {
        return new JsonResponse(array(
            'defaultLabel' => $this->get('translator')->trans('Choose a State')
        ));
    }

    /**
     * @Route("/state/{state}/city", name="cekurte_custom_user_filter_city_by_state_id", options={"expose"=true}))
     * @Method("GET")
     * @Template()
     */
    public function filterCityByStateIdAction($state)
    {
        $data = $this->getEntityRepository()->findBy(array(
            'state' => $state
        ));

        $translator = $this->get('translator');

        if (empty($data)) {
            return new JsonResponse(array(
                'success'   => false,
                'message'   => $translator->trans('No records found!'),
            ));
        }

        $dataResponse = array();

        foreach ($data as $city) {

            if ($city instanceof City) {
                $dataResponse[] = array(
                    'id'    => $city->getId(),
                    'name'  => $city->getName(),
                );
            }
        }

        return new JsonResponse(array(
            'success'       => true,
            'message'       => $translator->trans('Matches Successful!'),
            'defaultLabel'  => $translator->trans('Choose a City'),
            'data'          => $dataResponse,
        ));
    }
}
