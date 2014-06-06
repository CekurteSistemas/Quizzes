<?php

namespace Cekurte\ZCPEBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DefaultFieldsBaseEntity
 *
 * @ORM\MappedSuperclass
 */
class DefaultFieldsBaseEntity extends DefaultFieldsCreatedBaseEntity
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     */
    protected $deleted;

    /**
     * @var \Cekurte\Custom\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Cekurte\Custom\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deleted_by", referencedColumnName="id", nullable=true)
     * })
     */
    protected $deletedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var \Cekurte\Custom\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Cekurte\Custom\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true)
     * })
     */
    protected $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * Set deleted
     *
     * @param boolean $isDeleted
     * @return mixed
     */
    public function setDeleted($isDeleted)
    {
        $this->deleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deletedBy
     *
     * @param \Cekurte\Custom\UserBundle\Entity\User $user
     *
     * @return mixed
     */
    public function setDeletedBy(\Cekurte\Custom\UserBundle\Entity\User $user = null)
    {
        $this->deletedBy = $user;

        return $this;
    }

    /**
     * Get deletedBy
     *
     * @return \Cekurte\Custom\UserBundle\Entity\User
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return mixed
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set updatedBy
     *
     * @param \Cekurte\Custom\UserBundle\Entity\User $user
     *
     * @return mixed
     */
    public function setUpdatedBy(\Cekurte\Custom\UserBundle\Entity\User $user = null)
    {
        $this->updatedBy = $user;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Cekurte\Custom\UserBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
