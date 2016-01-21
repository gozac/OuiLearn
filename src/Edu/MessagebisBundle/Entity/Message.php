<?php

namespace Edu\MessagebisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edu\UserBundle\Entity\User;
use FOS\Message\Api\Model\ParticipantInterface;
use FOS\Message\Api\Model\ThreadInterface;
use FOS\Message\Driver\DoctrineORM\Entity\Message as BaseMessage;

/**
 * @ORM\Table(name="messages")
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="messages", cascade={"all"})
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User", cascade={"all"})
     * @var ParticipantInterface
     */
    protected $author;

    /**
     * @ORM\OneToMany(targetEntity="MessageMetadata", mappedBy="message", cascade={"all"})
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Add metadata
     *
     * @param \Edu\MessagebisBundle\Entity\MessageMetadata $metadata
     * @return Message
     */
    public function addMetadatum(\Edu\MessagebisBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Edu\MessagebisBundle\Entity\MessageMetadata $metadata
     */
    public function removeMetadatum(\Edu\MessagebisBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
