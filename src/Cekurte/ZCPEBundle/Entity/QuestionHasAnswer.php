<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionHasAnswer
 *
 * @ORM\Table(name="question_has_answer")
 * @ORM\Entity
 */
class QuestionHasAnswer
{
    /**
     * @var \Question
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $question;

    /**
     * @var \Answer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="answer_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $answer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_correct", type="boolean", nullable=false)
     */
    private $correct;


    /**
     * Set correct
     *
     * @param boolean $correct
     * @return QuestionHasAnswer
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }

    /**
     * Get correct
     *
     * @return boolean
     */
    public function isCorrect()
    {
        return $this->correct;
    }

    /**
     * Set question
     *
     * @param \Cekurte\ZCPEBundle\Entity\Question $question
     * @return QuestionHasAnswer
     */
    public function setQuestion(\Cekurte\ZCPEBundle\Entity\Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Cekurte\ZCPEBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param \Cekurte\ZCPEBundle\Entity\Answer $answer
     * @return QuestionHasAnswer
     */
    public function setAnswer(\Cekurte\ZCPEBundle\Entity\Answer $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \Cekurte\ZCPEBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
