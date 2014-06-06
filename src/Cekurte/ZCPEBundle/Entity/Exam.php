<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam")
 * @ORM\Entity
 */
class Exam extends DefaultFieldsCreatedBaseEntity
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
     * @var \DateTime
     *
     * @ORM\Column(name="started_in", type="datetime", nullable=false)
     */
    private $startedIn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_in", type="datetime", nullable=false)
     */
    private $finishedIn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="time", nullable=true)
     */
    private $duration;

    /**
     * @var \Plataform
     *
     * @ORM\ManyToOne(targetEntity="Plataform")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plataform_id", referencedColumnName="id")
     * })
     */
    private $plataform;

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
     * Set startedIn
     *
     * @param \DateTime $startedIn
     * @return Exam
     */
    public function setStartedIn($startedIn)
    {
        $this->startedIn = $startedIn;

        return $this;
    }

    /**
     * Get startedIn
     *
     * @return \DateTime
     */
    public function getStartedIn()
    {
        return $this->startedIn;
    }

    /**
     * Set finishedIn
     *
     * @param \DateTime $finishedIn
     * @return Exam
     */
    public function setFinishedIn($finishedIn)
    {
        $this->finishedIn = $finishedIn;

        return $this;
    }

    /**
     * Get finishedIn
     *
     * @return \DateTime
     */
    public function getFinishedIn()
    {
        return $this->finishedIn;
    }

    /**
     * Set duration
     *
     * @param \DateTime $duration
     * @return Exam
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set plataform
     *
     * @param \Cekurte\ZCPEBundle\Entity\Plataform $plataform
     * @return Exam
     */
    public function setPlataform(\Cekurte\ZCPEBundle\Entity\Plataform $plataform = null)
    {
        $this->plataform = $plataform;

        return $this;
    }

    /**
     * Get plataform
     *
     * @return \Cekurte\ZCPEBundle\Entity\Plataform
     */
    public function getPlataform()
    {
        return $this->plataform;
    }
}
