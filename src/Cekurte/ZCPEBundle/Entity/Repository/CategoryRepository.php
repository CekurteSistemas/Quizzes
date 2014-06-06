<?php

namespace Cekurte\ZCPEBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Cekurte\ZCPEBundle\Entity\Category;

/**
 * Category Repository.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Search for records based on an entity
     *
     * @param Category $entity
     * @param string $sort
     * @param string $direction
     * @return \Doctrine\ORM\Query
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getQuery(Category $entity, $sort, $direction)
    {
        $queryBuilder = $this->createQueryBuilder('ck');

        $data = array(
            'title' => $entity->getTitle(),
            'description' => $entity->getDescription(),
        );

        if (!empty($data['title'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.title', ':title'))
                ->setParameter('title', "%{$data['title']}%")
            ;
        }

        if (!empty($data['description'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.description', ':description'))
                ->setParameter('description', "%{$data['description']}%")
            ;
        }

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('ck.deleted', ':deleted'))
            ->setParameter('deleted', false)
        ;

        return $queryBuilder
            ->orderBy($sort, $direction)
            ->getQuery()
        ;
    }
}
