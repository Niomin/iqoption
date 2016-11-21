<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function loadTree()
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.childrenCategories', 'children')
            ->select('c, children');
        return $qb->getQuery()->getResult();
    }
}