<?php

namespace Edu\QcmBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Edu\QcmBundle\Entity\Questionnaire;

class Reponse
{

    private $resultat;

    public function __construct()
    {
    }

    public function getResultat()
    {
        return $this->resultat;
    }

    public function setResultat($resultat)
    {
        $this->resultat = $resultat;

        return $this;
    }
}