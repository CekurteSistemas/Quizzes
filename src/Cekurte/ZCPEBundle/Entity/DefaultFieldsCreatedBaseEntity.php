<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultFieldsCreatedBaseEntity
 *
 * @ORM\MappedSuperclass
 */
class DefaultFieldsCreatedBaseEntity
{
    /**
     * @var \Cekurte\Custom\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Cekurte\Custom\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
     * })
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Set createdBy
     *
     * @param \Cekurte\Custom\UserBundle\Entity\User $user
     *
     * @return mixed
     */
    public function setCreatedBy(\Cekurte\Custom\UserBundle\Entity\User $user = null)
    {
        $this->createdBy = $user;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Cekurte\Custom\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
