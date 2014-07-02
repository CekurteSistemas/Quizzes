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

        $token = $container->get('security.context')->getToken();

        $service = $container->get('cekurte_google_api.gmail');

        if ($service->getClient()->isAccessTokenExpired()) {
            $service->getClient()->refreshToken(
                $token->getRefreshToken()
            );
        }

        try {

            $message = \Swift_Message::newInstance();

            $message
                ->addTo(
                    $container->getParameter('cekurte_zcpe_google_group_mail'),
                    $container->getParameter('cekurte_zcpe_google_group_name')
                )
                ->addFrom(
                    $this->getUser()->getEmail(),
                    $this->getUser()->getName()
                )
                ->setSubject(
                    $this->getSubject($question)
                )

                ->setContentType('text/html')
                ->setBody(
                    $this->getTemplateBody($question)
                )

                ->setEncoder(\Swift_Encoding::getBase64Encoding())
                ->setCharset('utf-8')
            ;

            $gmailMessage = new \Google_Service_Gmail_Message();

            $gmailMessage->setRaw(base64_encode($message->toString()));

            $service->users_messages->send('me', $gmailMessage);

            $container->get('session')->getFlashBag()->add('message', array(
                'type'      => 'success',
                'message'   => $container->get('translator')->trans('The email has been sent successfully.'),
            ));

        } catch (\Google_Service_Exception $e) {

            $container->get('session')->getFlashBag()->add('message', array(
                'type'      => 'error',
                'message'   => $e->getMessage(),
            ));
        }
    }
}
