<?php

namespace AccessibilityBarriersBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryReNotificationRepositorypository
 */
class NotificationRepository extends EntityRepository
{
    public function getNotificationsCount()
    {
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result;
    }
}