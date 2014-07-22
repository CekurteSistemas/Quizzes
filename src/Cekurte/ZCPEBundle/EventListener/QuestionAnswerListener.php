<?php

namespace Cekurte\ZCPEBundle\EventListener;

use Cekurte\ComponentBundle\Util\ContainerAware;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * Get a question type of question
     *
     * @param Question $question
     *
     * @return string
     */
    public function getQuestionType(Question $question)
    {
        return $question->getQuestionType()->getTitle();
    }

    /**
     * Get the categories of question
     *
     * @param Question $question
     *
     * @return array
     */
    public function getCategories(Question $question)
    {
        $categories = $question->getCategory()->getValues();

        $data = array();

        foreach ($categories as $category) {
            $data[] = $category->getTitle();
        }

        return $data;
    }

    /**
     * @param Question $question
     *
     * @return mixed
     */
    public function getTemplateBody(Question $question)
    {
        $filename = 'CekurteZCPEBundle::email.txt.twig';

        return $this->getContainer()->get('templating')->render($filename, array(
            'footer'                => '@CekurteSistemas',
            'subject'               => $this->getSubject($question),
            'question'              => $question,
            'questionType'          => $this->getQuestionType($question),
            'questionCategories'    => $this->getCategories($question),
        ));
    }

    /**
     * Get subject
     *
     * @param Question $question
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
                    $container->getParameter('cekurte_zcpe_google_group_mail')
                )
                ->addFrom(
                    $this->getUser()->getEmail()
                )
                ->setSubject(
                    $this->getSubject($question)
                )

                ->setBody(
                    $this->getTemplateBody($question), 'text/plain'
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

            $question->setEmailHasSent(true);

            $em = $this->getContainer()->get('doctrine')->getManager();

            $em->persist($question);
            $em->flush();

        } catch (\Google_Service_Exception $e) {

            $container->get('session')->getFlashBag()->add('message', array(
                'type'      => 'error',
                'message'   => $e->getMessage(),
            ));
        }
    }
}
