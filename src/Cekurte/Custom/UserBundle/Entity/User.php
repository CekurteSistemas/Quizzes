<?php

namespace Cekurte\Custom\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="auth_user")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Cekurte\Custom\UserBundle\Entity\Group", inversedBy="users")
     * @ORM\JoinTable(name="auth_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotNull()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    protected $picture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    protected $birthday;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    protected $deleted;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin_id", type="string", length=255, nullable=true)
     */
    protected $linkedin_id;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin_access_token", type="string", length=255, nullable=true)
     */
    protected $linkedin_access_token;

    /**
     * @var string
     *
     * @ORM\Column(name="google_plus_id", type="string", length=255, nullable=true)
     */
    protected $google_plus_id;

    /**
     * @var string
     *
     * @ORM\Column(name="google_plus_token", type="string", length=255, nullable=true)
     */
    protected $google_plus_token;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_id", type="string", length=255, nullable=true)
     */
    protected $twitter_id;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_token", type="string", length=255, nullable=true)
     */
    protected $twitter_token;

    /**
     * @var string
     *
     * @ORM\Column(name="sound_cloud_id", type="string", length=255, nullable=true)
     */
    protected $sound_cloud_id;

    /**
     * @var string
     *
     * @ORM\Column(name="sound_cloud_token", type="string", length=255, nullable=true)
     */
    protected $sound_cloud_token;

    /**
     * @var string
     *
     * @ORM\Column(name="myspace_id", type="string", length=255, nullable=true)
     */
    protected $myspace_id;

    /**
     * @var string
     *
     * @ORM\Column(name="myspace_token", type="string", length=255, nullable=true)
     */
    protected $myspace_token;

    /**
     * @var \City
     *
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    protected $city;

    /**
     * {@inherited}
     */
    public function __construct()
    {
        parent::__construct();

        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set linkedin_id
     *
     * @param string $linkedinId
     * @return User
     */
    public function setLinkedinId($linkedinId)
    {
        $this->linkedin_id = $linkedinId;

        return $this;
    }

    /**
     * Get linkedin_id
     *
     * @return string
     */
    public function getLinkedinId()
    {
        return $this->linkedin_id;
    }

    /**
     * Set linkedin_access_token
     *
     * @param string $linkedinAccessToken
     * @return User
     */
    public function setLinkedinAccessToken($linkedinAccessToken)
    {
        $this->linkedin_access_token = $linkedinAccessToken;

        return $this;
    }

    /**
     * Get linkedin_access_token
     *
     * @return string
     */
    public function getLinkedinAccessToken()
    {
        return $this->linkedin_access_token;
    }

    /**
     * Set google_plus_id
     *
     * @param string $googlePlusId
     * @return User
     */
    public function setGooglePlusId($googlePlusId)
    {
        $this->google_plus_id = $googlePlusId;

        return $this;
    }

    /**
     * Get google_plus_id
     *
     * @return string
     */
    public function getGooglePlusId()
    {
        return $this->google_plus_id;
    }

    /**
     * Set google_plus_token
     *
     * @param string $googlePlusToken
     * @return User
     */
    public function setGooglePlusToken($googlePlusToken)
    {
        $this->google_plus_token = $googlePlusToken;

        return $this;
    }

    /**
     * Get google_plus_token
     *
     * @return string
     */
    public function getGooglePlusToken()
    {
        return $this->google_plus_token;
    }

    /**
     * Set twitter_id
     *
     * @param string $twitterId
     * @return User
     */
    public function setTwitterId($twitterId)
    {
        $this->twitter_id = $twitterId;

        return $this;
    }

    /**
     * Get twitter_id
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * Set twitter_token
     *
     * @param string $twitterToken
     * @return User
     */
    public function setTwitterToken($twitterToken)
    {
        $this->twitter_token = $twitterToken;

        return $this;
    }

    /**
     * Get twitter_token
     *
     * @return string
     */
    public function getTwitterToken()
    {
        return $this->twitter_token;
    }

    /**
     * Set sound_cloud_id
     *
     * @param string $soundCloudId
     * @return User
     */
    public function setSoundCloudId($soundCloudId)
    {
        $this->sound_cloud_id = $soundCloudId;

        return $this;
    }

    /**
     * Get sound_cloud_id
     *
     * @return string
     */
    public function getSoundCloudId()
    {
        return $this->sound_cloud_id;
    }

    /**
     * Set sound_cloud_token
     *
     * @param string $soundCloudToken
     * @return User
     */
    public function setSoundCloudToken($soundCloudToken)
    {
        $this->sound_cloud_token = $soundCloudToken;

        return $this;
    }

    /**
     * Get sound_cloud_token
     *
     * @return string
     */
    public function getSoundCloudToken()
    {
        return $this->sound_cloud_token;
    }

    /**
     * Set myspace_id
     *
     * @param string $myspaceId
     * @return User
     */
    public function setMyspaceId($myspaceId)
    {
        $this->myspace_id = $myspaceId;

        return $this;
    }

    /**
     * Get myspace_id
     *
     * @return string
     */
    public function getMyspaceId()
    {
        return $this->myspace_id;
    }

    /**
     * Set myspace_token
     *
     * @param string $myspaceToken
     * @return User
     */
    public function setMyspaceToken($myspaceToken)
    {
        $this->myspace_token = $myspaceToken;

        return $this;
    }

    /**
     * Get myspace_token
     *
     * @return string
     */
    public function getMyspaceToken()
    {
        return $this->myspace_token;
    }

    /**
     * Set city
     *
     * @param \Cekurte\Custom\UserBundle\Entity\City $city
     * @return User
     */
    public function setCity(\Cekurte\Custom\UserBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Cekurte\Custom\UserBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }
}
