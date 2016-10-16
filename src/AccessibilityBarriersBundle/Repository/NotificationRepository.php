<?php

namespace AccessibilityBarriersBundle\Repository;

use ApiBundle\Model\NearMeCriteria;
use ApiBundle\Model\NotificationCriteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class NotificationRepository
 */
class NotificationRepository extends EntityRepository
{

    const LIMIT = 10;

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

    public function findNearNotifications(NearMeCriteria $criteria)
    {
        $builder = $this->createQueryBuilder('s');
        $builder->select('s as notification');

        if ($criteria->latitude && $criteria->longitude) {
            $builder->addSelect(
                '( 6371 * acos(cos(radians(' . $criteria->latitude . '))' .
                '* cos( radians( s.latitude ) )' .
                '* cos( radians( s.longitude )' .
                '- radians(' . $criteria->longitude . ') )' .
                '+ sin( radians(' . $criteria->latitude . ') )' .
                '* sin( radians( s.latitude ) ) ) ) as distance'
            );
        }

        $limit = $criteria->limit ? $criteria->limit : self::LIMIT;
        $builder->setMaxResults($limit);
        $builder->orderBy('distance', 'ASC');

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
