<?php

namespace Edu\FormaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Content
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Content
{

    /**
     * @ORM\OneToOne(targetEntity="Edu\FormaBundle\Entity\Content", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $suite;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Edu\QcmBundle\Entity\Questionnaire", mappedBy="content", cascade={"persist", "remove"})
     */
    private $questionnaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="score_minimum", type="integer")
     */
    private $score_minimum = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="text", nullable=true)
     */
    private $texte;

    /**
     * @Assert\File(
     *     maxSize = "500M",
     *     mimeTypes = {"video/mpeg", "video/mp4", "video/quicktime", "video/x-ms-wmv", "video/x-msvideo", "video/x-flv"},
     *     mimeTypesMessage = "ce format de video est inconnu",
     *     uploadIniSizeErrorMessage = "uploaded file is larger than the upload_max_filesize PHP.ini setting",
     *     uploadFormSizeErrorMessage = "uploaded file is larger than allowed by the HTML file input field",
     *     uploadErrorMessage = "uploaded file could not be uploaded for some unknown reason",
     *     maxSizeMessage = "fichier trop volumineux"
     * )
     */
    public $file;

    public function __construct(){
        $this->questionnaire   = new ArrayCollection();
    }

    //les 4 fonctions suivantes sont pour le upload
    public function getAbsolutePath()
    {
        return null === $this->url ? null : $this->getUploadRootDir().'/'.$this->url;
    }

    public function getWebPath()
    {
        return null === $this->url ? null : $this->getUploadDir().'/'.$this->url;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/videos';
    }

    // **** les 3 fonctions suivantes servent à gérer le callback et l'upload de file
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        var_dump($this->file);

        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $this->url = uniqid().'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // ** on peut mettre ça si on veut faire que le nom corresponde au nom de l'image original
        //$this->setName($this->file->getClientOriginalName());

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->url);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * Set url
     *
     * @param string $url
     * @return Content
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set texte
     *
     * @param string $texte
     * @return Content
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set advert
     *
     * @param \Edu\FormaBundle\Entity\Advert $advert
     * @return Content
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
     * Set suite
     *
     * @param \Edu\FormaBundle\Entity\Content $suite
     * @return Content
     */
    public function setSuite(Content $suite = null)
    {
        $this->suite = $suite;

        return $this;
    }

    /**
     * Get suite
     *
     * @return \Edu\FormaBundle\Entity\Content 
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * Set score_minimum
     *
     * @param integer $scoreMinimum
     * @return Content
     */
    public function setScoreMinimum($scoreMinimum)
    {
        $this->score_minimum = $scoreMinimum;

        return $this;
    }

    /**
     * Get score_minimum
     *
     * @return integer 
     */
    public function getScoreMinimum()
    {
        return $this->score_minimum;
    }

    /**
     * Add questionnaire
     *
     * @param \Edu\QcmBundle\Entity\Questionnaire $questionnaire
     * @return Content
     */
    public function addQuestionnaire(\Edu\QcmBundle\Entity\Questionnaire $questionnaire)
    {
        $this->questionnaire[] = $questionnaire;

        return $this;
    }

    /**
     * Remove questionnaire
     *
     * @param \Edu\QcmBundle\Entity\Questionnaire $questionnaire
     */
    public function removeQuestionnaire(\Edu\QcmBundle\Entity\Questionnaire $questionnaire)
    {
        $this->questionnaire->removeElement($questionnaire);
    }

    /**
     * Get questionnaire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }
}
