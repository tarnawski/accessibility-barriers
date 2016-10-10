<?php

namespace AccessibilityBarriersBundle\Repository;

use ApiBundle\Model\NotificationCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class NotificationRepository
 */
class NotificationRepository extends EntityRepository
{

    public function findAllNotDistributed()
    {
        $result = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.send = false')
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findByCriteria(NotificationCriteria $criteria)
    {
        $builder = $this->createQueryBuilder('n');

        if ($criteria->name) {
            $builder->andWhere('n.name LIKE :name');
            $builder->setParameter('name', '%' . $criteria->name . '%');
        }
        if ($criteria->description) {
            $builder->andWhere('n.description LIKE :description');
            $builder->setParameter('description', '%' . $criteria->description . '%');
        }
        if ($criteria->limit) {
            $builder->setMaxResults($criteria->limit);
        }
        $builder->addOrderBy('n.createdAt', 'DESC');

        return $builder->getQuery()->getResult();
    }

    public function getNotificationsCount()
    {
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();
        
        return $result;
    }
}
