<?php

namespace Edu\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function getAdvertonUser($advert){
        $qb = $this
            ->createQueryBuilder('u')
            ->Join('u.followadvert', 'a')
            ->addSelect('a');

        $qb->where($qb->expr()->in('a', $advert));

        return $qb->getQuery()->getResult();
    }
}
