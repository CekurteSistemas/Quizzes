<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamHasQuestion
 *
 * @ORM\Table(name="exam_has_question", indexes={@ORM\Index(name="fk_exam_has_question_question1_idx", columns={"question_id"}), @ORM\Index(name="fk_exam_has_question_exam1_idx", columns={"exam_id"}), @ORM\Index(name="fk_exam_has_question_answer1_idx", columns={"answer_id"})})
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


}
