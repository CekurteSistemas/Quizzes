<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamHasQuestion
 *
 * @ORM\Table(name="exam_has_question")
 * @ORM\Entity
 */
class ExamHasQuestion
{
    /**
     * @var string
     *
     * @ORM\Column(name="answer_text", type="string", length=255, nullable=true)
     */
    private $answerText;

    /**
     * @var string
     *
     * @ORM\Column(name="answer_comment", type="text", nullable=true)
     */
    private $answerComment;

    /**
     * @var \Exam
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     * })
     */
    private $exam;

    /**
     * @var \Question
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;

    /**
     * @var \Answer
     *
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     * })
     */
    private $answer;

    /**
     * Set answerText
     *
     * @param string $answerText
     * @return ExamHasQuestion
     */
    public function setAnswerText($answerText)
    {
        $this->answerText = $answerText;

        return $this;
    }

    /**
     * Get answerText
     *
     * @return string
     */
    public function getAnswerText()
    {
        return $this->answerText;
    }

    /**
     * Set answerComment
     *
     * @param string $answerComment
     * @return ExamHasQuestion
     */
    public function setAnswerComment($answerComment)
    {
        $this->answerComment = $answerComment;

        return $this;
    }

    /**
     * Get answerComment
     *
     * @return string
     */
    public function getAnswerComment()
    {
        return $this->answerComment;
    }

    /**
     * Set exam
     *
     * @param \Cekurte\ZCPEBundle\Entity\Exam $exam
     * @return ExamHasQuestion
     */
    public function setExam(\Cekurte\ZCPEBundle\Entity\Exam $exam)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam
     *
     * @return \Cekurte\ZCPEBundle\Entity\Exam
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set question
     *
     * @param \Cekurte\ZCPEBundle\Entity\Question $question
     * @return ExamHasQuestion
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
     * @return ExamHasQuestion
     */
    public function setAnswer(\Cekurte\ZCPEBundle\Entity\Answer $answer = null)
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
