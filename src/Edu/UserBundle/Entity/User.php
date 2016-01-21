<?php
// src/Edu/UserBundle/Entity/User.php

namespace Edu\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\Message\Api\Model\ParticipantInterface;

/**
 * @ORM\Entity
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(name="factory", type="string", length=255, nullable=true)
     */
    private $factory = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbfollow", type="integer")
     */
    private $nbfollow = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nblike", type="integer")
     */
    private $nblike = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbcoach", type="integer")
     */
    private $nbcoach = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="beauthor", type="boolean")
     */
    private $beauthor = false;

    /**
     * Set factory
     *
     * @param array $factory
     * @return User
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * Get factory
     *
     * @return array 
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Set beauthor
     *
     * @param boolean $beauthor
     * @return User
     */
    public function setBeauthor($beauthor)
    {
        $this->beauthor = $beauthor;

        return $this;
    }

    /**
     * Get beauthor
     *
     * @return boolean 
     */
    public function getBeauthor()
    {
        return $this->beauthor;
    }

    public function increaseFollow()
    {
        $this->nbfollow++;
    }

    /**
     * Set nbfollow
     *
     * @param integer $nbfollow
     * @return User
     */
    public function setNbfollow($nbfollow)
    {
        $this->nbfollow = $nbfollow;

        return $this;
    }

    /**
     * Get nbfollow
     *
     * @return integer 
     */
    public function getNbfollow()
    {
        return $this->nbfollow;
    }

    public function increaseLike()
    {
        $this->nblike++;
    }

    /**
     * Set nblike
     *
     * @param integer $nblike
     * @return User
     */
    public function setNblike($nblike)
    {
        $this->nblike = $nblike;

        return $this;
    }

    /**
     * Get nblike
     *
     * @return integer 
     */
    public function getNblike()
    {
        return $this->nblike;
    }

    public function increaseCoach()
    {
        $this->nbcoach++;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbcoach
     *
     * @param integer $nbcoach
     * @return User
     */
    public function setNbcoach($nbcoach)
    {
        $this->nbcoach = $nbcoach;

        return $this;
    }

    /**
     * Get nbcoach
     *
     * @return integer 
     */
    public function getNbcoach()
    {
        return $this->nbcoach;
    }
}
