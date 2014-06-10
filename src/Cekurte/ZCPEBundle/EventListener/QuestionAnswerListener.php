<?php

namespace Cekurte\ZCPEBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cekurte\ComponentBundle\Util\ContainerAware;
use Cekurte\ZCPEBundle\Event\QuestionAnswerEvent;
use Cekurte\ZCPEBundle\Events;

/**
 * QuestionAnswerListener
 *
 * @author João Paulo Cercal <sistemas@cekurte>
 * @version 1.0
 */
class QuestionAnswerListener extends ContainerAware implements EventSubscriberInterface
{
    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * {@inherited}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::NEW_QUESTION => array(
                array('onCreateNewQuestion', 100),
            ),
        );
    }

    /**
     * onCreateNewQuestion
     *
     * @param QuestionAnswerEvent $event
     *
     * @return void
     */
    public function onCreateNewQuestion(QuestionAnswerEvent $event)
    {
        $container = $this->getContainer();

        $filename = 'CekurteZCPEBundle::email.html.twig';

        $body = $container->get('templating')->render($filename, array(
            'question' => $event->getQuestion()
        ));


        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf(
                '[%s] %s: %s',
                $container->getParameter('cekurte_zcpe_google_group_name'),
                $container->getParameter('cekurte_zcpe_google_group_subject'),
                $event->getQuestion()->getGoogleGroupsId()
            ))
            ->setFrom(array(
                $this->getUser()->getEmail() => $this->getUser()->getName()
            ))
            ->setTo(array(
                $container->getParameter('cekurte_zcpe_google_group_mail') => $container->getParameter('cekurte_zcpe_google_group_name')
            ))
            ->setContentType('text/html')
            ->setBody($body)
        ;

        $success = $container->get('mailer')->send($message, $failures);

        if ($success == 1) {
            $container->get('session')->getFlashBag()->add('message', array(
                'type'      => 'success',
                'message'   => $container->get('translator')->trans('The email has been sent successfully.'),
            ));
        }

        return;
    }
}
