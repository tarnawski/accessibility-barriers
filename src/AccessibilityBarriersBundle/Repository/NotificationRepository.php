<?php

namespace AccessibilityBarriersBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NotificationRepository
 */
class NotificationRepository extends EntityRepository
{
    public function findByQuery($query = null)
    {
        $builder = $this->createQueryBuilder('n');
        if ($query) {
            $builder->andWhere('(n.name LIKE :query) OR (n.description LIKE :query)');
            $builder->setParameter('query', '%' . $query. '%');
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
