<?php

namespace Edu\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Followadvert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edu\UserBundle\Entity\FollowadvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Followadvert
{
    /**
     * @ORM\ManyToOne(targetEntity="Edu\FormaBundle\Entity\Advert")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;


    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Finished", type="boolean")
     */
    private $finished = false;



    /**
     * @var integer
     *
     * @ORM\Column(name="comprehension", type="smallint", nullable=true)
     */
    private $comprehension;

    /**
     * @var integer
     *
     * @ORM\Column(name="resolution", type="smallint", nullable=true)
     */
    private $resolution;

    /**
     * @var integer
     *
     * @ORM\Column(name="logique", type="smallint", nullable=true)
     */
    private $logique;

    /**
     * @var integer
     *
     * @ORM\Column(name="ludique", type="smallint", nullable=true)
     */
    private $ludique;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbqcm", type="integer", nullable=true)
     */
    private $nbqcm = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="text", nullable=true)
     */
    private $remarque;

    /**
     * @ORM\Column(name="coaching", type="boolean")
     */
    private $coaching = false;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct()
    {
        //$this->finished = false;
        $this->date = new \Datetime();
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getAdvert()->increaseFollow();
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
     * Set finished
     *
     * @param boolean $finished
     * @return Followadvert
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean 
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set advert
     *
     * @param \Edu\FormaBundle\Entity\Advert $advert
     * @return Followadvert
     */
    public function setAdvert(\Edu\FormaBundle\Entity\Advert $advert)
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get advert
     *
     * @return \Edu\FormaBundle\Entity\Advert 
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * Set user
     *
     * @param \Edu\UserBundle\Entity\User $user
     * @return Followadvert
     */
    public function setUser(\Edu\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Edu\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set coach
     *
     * @param boolean $coach
     * @return Followadvert
     */
    public function setCoach($coach)
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * Get coach
     *
     * @return boolean 
     */
    public function getCoach()
    {
        return $this->coach;
    }

    /**
     * Set comprehension
     *
     * @param integer $comprehension
     * @return Followadvert
     */
    public function setComprehension($comprehension)
    {
        $this->comprehension = $comprehension;

        return $this;
    }

    /**
     * Get comprehension
     *
     * @return integer 
     */
    public function getComprehension()
    {
        return $this->comprehension;
    }

    /**
     * Set resolution
     *
     * @param integer $resolution
     * @return Followadvert
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return integer 
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set logique
     *
     * @param integer $logique
     * @return Followadvert
     */
    public function setLogique($logique)
    {
        $this->logique = $logique;

        return $this;
    }

    /**
     * Get logique
     *
     * @return integer 
     */
    public function getLogique()
    {
        return $this->logique;
    }

    /**
     * Set ludique
     *
     * @param integer $ludique
     * @return Followadvert
     */
    public function setLudique($ludique)
    {
        $this->ludique = $ludique;

        return $this;
    }

    /**
     * Get ludique
     *
     * @return integer 
     */
    public function getLudique()
    {
        return $this->ludique;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     * @return Followadvert
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string 
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Followadvert
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set coaching
     *
     * @param boolean $coaching
     * @return Followadvert
     */
    public function setCoaching($coaching)
    {
        $this->coaching = $coaching;

        return $this;
    }

    /**
     * Get coaching
     *
     * @return boolean 
     */
    public function getCoaching()
    {
        return $this->coaching;
    }

    /**
     * Set nbqcm
     *
     * @param integer $nbqcm
     * @return Followadvert
     */
    public function setNbqcm($nbqcm)
    {
        $this->nbqcm = $nbqcm;

        return $this;
    }

    public function decreaseNbqcm()
    {
        $this->nbqcm -= 1;
        if ($this->nbqcm < 0)
            $this->nbqcm = 0;

        return $this;
    }

    /**
     * Get nbqcm
     *
     * @return integer 
     */
    public function getNbqcm()
    {
        return $this->nbqcm;
    }
}
