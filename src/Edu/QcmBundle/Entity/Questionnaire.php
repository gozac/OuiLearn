<?php

namespace Edu\QcmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Questionnaire
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Questionnaire
{

    /**
     * @ORM\ManyToOne(targetEntity="Edu\FormaBundle\Entity\Content", inversedBy="questionnaire")
     * @ORM\JoinColumn(nullable=true)
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=255)
     * @Assert\Length(min=2)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_vrai", type="string", length=255)
     */
    private $reponse_vrai;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_fausse1", type="string", length=255, nullable=true)
     */
    private $reponse_fausse1;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_fausse2", type="string", length=255, nullable=true)
     */
    private $reponse_fausse2;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_fausse3", type="string", length=255, nullable=true)
     */
    private $reponse_fausse3;

    public function __construct()
    {
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
     * Set question
     *
     * @param string $question
     * @return Questionnaire
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set reponse_vrai
     *
     * @param string $reponseVrai
     * @return Questionnaire
     */
    public function setReponseVrai($reponseVrai)
    {
        $this->reponse_vrai = $reponseVrai;

        return $this;
    }

    /**
     * Get reponse_vrai
     *
     * @return string 
     */
    public function getReponseVrai()
    {
        return $this->reponse_vrai;
    }

    /**
     * Set reponse_fausse1
     *
     * @param string $reponseFausse1
     * @return Questionnaire
     */
    public function setReponseFausse1($reponseFausse1)
    {
        $this->reponse_fausse1 = $reponseFausse1;

        return $this;
    }

    /**
     * Get reponse_fausse1
     *
     * @return string 
     */
    public function getReponseFausse1()
    {
        return $this->reponse_fausse1;
    }

    /**
     * Set reponse_fausse2
     *
     * @param string $reponseFausse2
     * @return Questionnaire
     */
    public function setReponseFausse2($reponseFausse2)
    {
        $this->reponse_fausse2 = $reponseFausse2;

        return $this;
    }

    /**
     * Get reponse_fausse2
     *
     * @return string 
     */
    public function getReponseFausse2()
    {
        return $this->reponse_fausse2;
    }

    /**
     * Set reponse_fausse3
     *
     * @param string $reponseFausse3
     * @return Questionnaire
     */
    public function setReponseFausse3($reponseFausse3)
    {
        $this->reponse_fausse3 = $reponseFausse3;

        return $this;
    }

    /**
     * Get reponse_fausse3
     *
     * @return string 
     */
    public function getReponseFausse3()
    {
        return $this->reponse_fausse3;
    }

    /**
     * Set content
     *
     * @param \Edu\FormaBundle\Entity\Content $content
     * @return Questionnaire
     */
    public function setContent(\Edu\FormaBundle\Entity\Content $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Edu\FormaBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }
}
