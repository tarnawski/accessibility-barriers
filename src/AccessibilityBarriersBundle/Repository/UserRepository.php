<?php

namespace AccessibilityBarriersBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 */
class UserRepository extends EntityRepository
{
    public function findWithEnableNotifications()
    {
        $result = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.emailNotification = true')
            ->getQuery()
            ->getResult();

        return $result;
    }
}
