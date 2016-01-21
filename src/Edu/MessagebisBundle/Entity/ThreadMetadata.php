<?php

namespace Edu\MessagebisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edu\UserBundle\Entity\User;
use FOS\Message\Api\Model\ParticipantInterface;
use FOS\Message\Api\Model\ThreadInterface;
use FOS\Message\Driver\DoctrineORM\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * @ORM\Table(name="threads_metdata")
 * @ORM\Entity
 */
class ThreadMetadata extends BaseThreadMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="metadata", cascade={"all"})
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User", cascade={"all"})
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
