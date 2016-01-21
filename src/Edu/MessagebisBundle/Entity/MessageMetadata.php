<?php

namespace Edu\MessagebisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edu\UserBundle\Entity\User;
use FOS\Message\Api\Model\MessageInterface;
use FOS\Message\Api\Model\ParticipantInterface;
use FOS\Message\Driver\DoctrineORM\Entity\MessageMetadata as BaseMessageMetadata;

/**
 * @ORM\Table(name="messages_metadata")
 * @ORM\Entity
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="metadata", cascade={"all"})
     * @var MessageInterface
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User", cascade={"all"})
     * @var ParticipantInterface
     */
    protected $participant;

    /**
     * Get read
     *
     * @return boolean 
     */
    public function getRead()
    {
        return $this->read;
    }
}
