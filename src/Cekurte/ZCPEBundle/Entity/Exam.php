<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_exam_plataform1_idx", columns={"plataform_id"})})
 * @ORM\Entity
 */
class Exam
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
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Plataform
     *
     * @ORM\ManyToOne(targetEntity="Plataform")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plataform_id", referencedColumnName="id")
     * })
     */
    private $plataform;


}
