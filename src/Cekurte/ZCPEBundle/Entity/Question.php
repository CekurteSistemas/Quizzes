<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="\Cekurte\ZCPEBundle\Entity\Repository\QuestionRepository")
 */
class Question extends DefaultFieldsBaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="google_groups_id", type="integer", nullable=true)
     */
    private $googleGroupsId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text", nullable=false)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="difficulty", type="integer", nullable=true)
     */
    private $difficulty;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var \QuestionType
     *
     * @ORM\ManyToOne(targetEntity="QuestionType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_type_id", referencedColumnName="id")
     * })
     */
    private $questionType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Answer", inversedBy="question")
     * @ORM\JoinTable(name="question_has_answer",
     *   joinColumns={
     *     @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     *   }
     * )
     */
    private $answer;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="question")
     * @ORM\JoinTable(name="question_has_category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *   }
     * )
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="question")
     * @ORM\JoinTable(name="question_has_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tag;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set googleGroupsId
     *
     * @param integer $googleGroupsId
     * @return Question
     */
    public function setGoogleGroupsId($googleGroupsId)
    {
        $this->googleGroupsId = $googleGroupsId;

        return $this;
    }

    /**
     * Get googleGroupsId
     *
     * @return integer
     */
    public function getGoogleGroupsId()
    {
        return $this->googleGroupsId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set difficulty
     *
     * @param integer $difficulty
     * @return Question
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return integer
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Question
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set questionType
     *
     * @param \Cekurte\ZCPEBundle\Entity\QuestionType $questionType
     * @return Question
     */
    public function setQuestionType(\Cekurte\ZCPEBundle\Entity\QuestionType $questionType = null)
    {
        $this->questionType = $questionType;

        return $this;
    }

    /**
     * Get questionType
     *
     * @return \Cekurte\ZCPEBundle\Entity\QuestionType
     */
    public function getQuestionType()
    {
        return $this->questionType;
    }

    /**
     * Add answer
     *
     * @param \Cekurte\ZCPEBundle\Entity\Answer $answer
     * @return Question
     */
    public function addAnswer(\Cekurte\ZCPEBundle\Entity\Answer $answer)
    {
        $this->answer[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \Cekurte\ZCPEBundle\Entity\Answer $answer
     */
    public function removeAnswer(\Cekurte\ZCPEBundle\Entity\Answer $answer)
    {
        $this->answer->removeElement($answer);
    }

    /**
     * Get answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Add category
     *
     * @param \Cekurte\ZCPEBundle\Entity\Category $category
     * @return Question
     */
    public function addCategory(\Cekurte\ZCPEBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Cekurte\ZCPEBundle\Entity\Category $category
     */
    public function removeCategory(\Cekurte\ZCPEBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add tag
     *
     * @param \Cekurte\ZCPEBundle\Entity\Tag $tag
     * @return Question
     */
    public function addTag(\Cekurte\ZCPEBundle\Entity\Tag $tag)
    {
        $this->tag[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Cekurte\ZCPEBundle\Entity\Tag $tag
     */
    public function removeTag(\Cekurte\ZCPEBundle\Entity\Tag $tag)
    {
        $this->tag->removeElement($tag);
    }

    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTag()
    {
        return $this->tag;
    }
}
