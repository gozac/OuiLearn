<?php

namespace Edu\MessagebisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Edu\UserBundle\Entity\User;
use FOS\Message\Api\Model\ParticipantInterface;
use FOS\Message\Driver\DoctrineORM\Entity\Thread as BaseThread;

/**
 * @ORM\Table(name="threads")
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Edu\UserBundle\Entity\User", cascade={"all"})
     * @var ParticipantInterface
     */
    protected $owner;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="thread", cascade={"all"})
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(targetEntity="ThreadMetadata", mappedBy="thread", cascade={"all"})
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Add metadata
     *
     * @param \Edu\MessagebisBundle\Entity\ThreadMetadata $metadata
     * @return Thread
     */
    public function addMetadatum(\Edu\MessagebisBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Edu\MessagebisBundle\Entity\ThreadMetadata $metadata
     */
    public function removeMetadatum(\Edu\MessagebisBundle\Entity\ThreadMetadata $metadata)
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
