<?php

namespace Cekurte\ZCPEBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Cekurte\ZCPEBundle\Entity\Question;

/**
 * Event Question and Answer
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 1.0
 */
class QuestionAnswerEvent extends Event
{
    /**
     * @var Question
     */
    protected $question;

    /**
     * @param Question $question
     */
    public function __construct(Question $question)
    {
        $this->setQuestion($question);
    }

    /**
     * Set question
     *
     * @param Question $question
     *
     * @return QuestionAnswerEvent
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
