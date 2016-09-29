<?php

namespace AccessibilityBarriersBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
    public function getCategoriesCount()
    {
        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }
}