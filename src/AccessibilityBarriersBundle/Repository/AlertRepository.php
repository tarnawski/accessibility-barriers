<?php

namespace AccessibilityBarriersBundle\Repository;

use Doctrine\ORM\EntityRepository;
use OAuthBundle\Entity\User;

/**
 * Class AlertRepository
 */
class AlertRepository extends EntityRepository
{
    public function getActiveAlertByUser(User $user)
    {
        $result = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.user = :user')
            ->andWhere('a.active = true')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
