<?php

namespace Edu\FormaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Edu\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Advert
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Edu\FormaBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une annonce existe déjà avec ce titre.")
 */
class Advert
{
    /**
     * @ORM\ManyToMany(targetEntity="Edu\FormaBundle\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\OneToOne(targetEntity="Edu\FormaBundle\Entity\Content", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $contentnew;

    /**
     * @ORM\OneToOne(targetEntity="Edu\FormaBundle\Entity\Image", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User")
     * @Assert\Valid()
     */
    private $author;

    // Vos autres attributs…
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min=7, minMessage="Le titre doit faire au moins {{ limit }} caractères.")
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(name="factory", type="string", length=255, nullable=true)
     */
    private $factory;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbfollow", type="integer")
     */
    private $nbfollow = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbqcm", type="integer")
     */
    private $nbqcm = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbcontent", type="integer")
     */
    private $nbcontent = 1;

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
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = false;

    public function __construct()
    {
        // Par défaut, la date de l'annonce est la date d'aujourd'hui
        $this->date = new \Datetime();
        $this->categories   = new ArrayCollection();
    }
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }
    public function setImage(Image $image = null)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
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
     * Set date
     *
     * @param \DateTime $date
     * @return Advert
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
     * Set title
     *
     * @param string $title
     * @return Advert
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
     * Set published
     *
     * @param boolean $published
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Add categories
     *
     * @param \Edu\FormaBundle\Entity\Category $categories
     * @return Advert
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Edu\FormaBundle\Entity\Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }



    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Advert
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

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set factory
     *
     * @param string $factory
     * @return Advert
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * Get factory
     *
     * @return string 
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Set nbfollow
     *
     * @param integer $nbfollow
     * @return Advert
     */
    public function setNbfollow($nbfollow)
    {
        $this->nbfollow = $nbfollow;

        return $this;
    }

    public function increaseFollow()
    {
        $this->nbfollow++;
        $this->getAuthor()->increaseFollow();
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

    /**
     * Set nblike
     *
     * @param integer $nblike
     * @return Advert
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

    public function increaseLike()
    {
        $this->nblike++;
        $this->getAuthor()->increaseLike();
    }

    /**
     * Set author
     *
     * @param User $author
     * @return Advert
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Edu\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set nbcoach
     *
     * @param integer $nbcoach
     * @return Advert
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

    public function increaseCoach()
    {
        $this->nbcoach++;
        $this->getAuthor()->increaseCoach();
    }

    /**
     * Set contentnew
     *
     * @param \Edu\FormaBundle\Entity\Content $contentnew
     * @return Advert
     */
    public function setContentnew(\Edu\FormaBundle\Entity\Content $contentnew = null)
    {
        $this->contentnew = $contentnew;

        return $this;
    }

    /**
     * Get contentnew
     *
     * @return \Edu\FormaBundle\Entity\Content 
     */
    public function getContentnew()
    {
        return $this->contentnew;
    }

    /**
     * Set nbcontent
     *
     * @param integer $nbcontent
     * @return Advert
     */
    public function setNbcontent($nbcontent)
    {
        $this->nbcontent = $nbcontent;

        return $this;
    }

    public function increaseContent()
    {
        $this->nbcontent++;
    }

    /**
     * Get nbcontent
     *
     * @return integer
     */
    public function getNbcontent()
    {
        return $this->nbcontent;
    }

    public function increaseqcm()
    {
        $this->nbqcm++;
    }

    /**
     * Set nbqcm
     *
     * @param integer $nbqcm
     * @return Advert
     */
    public function setNbqcm($nbqcm)
    {
        $this->nbqcm = $nbqcm;

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
