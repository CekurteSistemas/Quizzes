<?php

namespace Cekurte\ZCPEBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cekurte\ComponentBundle\Util\ContainerAware;
use Cekurte\ZCPEBundle\Event\QuestionAnswerEvent;
use Cekurte\ZCPEBundle\Events;
use Cekurte\ZCPEBundle\Entity\Question;

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

    public function getTemplateBody(Question $question)
    {
        $filename = 'CekurteZCPEBundle::email.html.twig';

        return $this->getContainer()->get('templating')->render($filename, array(
            'title'     => 'Cekurte ZCPE',
            'footer'    => '@CekurteZCPE 2014',
            'subject'   => $this->getSubject($question),
            'question'  => $question,
        ));
    }

    /**
     * Get subject
     *
     * @return string
     */
    protected function getSubject(Question $question)
    {
        $container = $this->getContainer();

        return sprintf(
            '[%s] %s: %s',
            $container->getParameter('cekurte_zcpe_google_group_name'),
            $container->getParameter('cekurte_zcpe_google_group_subject'),
            $question->getGoogleGroupsId()
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

        $question = $event->getQuestion();

        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject($question))
            ->setFrom(array(
                $this->getUser()->getEmail() => $this->getUser()->getName()
            ))
            ->setTo(array(
                $container->getParameter('cekurte_zcpe_google_group_mail') => $container->getParameter('cekurte_zcpe_google_group_name')
            ))
            ->setContentType('text/html')
            ->setBody($this->getTemplateBody($question))
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
