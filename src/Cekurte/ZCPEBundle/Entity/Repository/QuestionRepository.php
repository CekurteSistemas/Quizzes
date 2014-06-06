<?php

namespace Cekurte\ZCPEBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Cekurte\ZCPEBundle\Entity\Question;

/**
 * Question Repository.
 *
 * @author João Paulo Cercal <sistemas@cekurte.com>
 * @version 0.1
 */
class QuestionRepository extends EntityRepository
{
    /**
     * Search for records based on an entity
     *
     * @param Question $entity
     * @param string $sort
     * @param string $direction
     * @return \Doctrine\ORM\Query
     *
     * @author João Paulo Cercal <sistemas@cekurte.com>
     * @version 0.1
     */
    public function getQuery(Question $entity, $sort, $direction)
    {
        $queryBuilder = $this->createQueryBuilder('ck');

        $data = array(
            //'questionType'  => $entity->getQuestionType(),
            //'category'      => $entity->getCategory(),
            'createdBy'         => $entity->getCreatedBy(),
            'googleGroupsId'    => $entity->getGoogleGroupsId(),
            'title'             => $entity->getTitle(),
            'difficulty'        => $entity->getDifficulty(),
            'comment'           => $entity->getComment(),
            'updatedBy'         => $entity->getUpdatedBy(),
        );

        if (!empty($data['questionType'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.questionType', ':questionType'))
                ->setParameter('questionType', $data['questionType'])
            ;
        }

        if (!empty($data['answer'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.answer', ':answer'))
                ->setParameter('answer', $data['answer'])
            ;
        }

        if (!empty($data['category'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.category', ':category'))
                ->setParameter('category', $data['category'])
            ;
        }

        if (!empty($data['tag'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.tag', ':tag'))
                ->setParameter('tag', $data['tag'])
            ;
        }

        if (!empty($data['deletedBy'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.deletedBy', ':deletedBy'))
                ->setParameter('deletedBy', $data['deletedBy'])
            ;
        }

        if (!empty($data['updatedBy'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.updatedBy', ':updatedBy'))
                ->setParameter('updatedBy', $data['updatedBy'])
            ;
        }

        if (!empty($data['createdBy'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.createdBy', ':createdBy'))
                ->setParameter('createdBy', $data['createdBy'])
            ;
        }

        if (!empty($data['googleGroupsId'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.googleGroupsId', ':googleGroupsId'))
                ->setParameter('googleGroupsId', $data['googleGroupsId'])
            ;
        }

        if (!empty($data['title'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->like('ck.title', ':title'))
                ->setParameter('title', "%{$data['title']}%")
            ;
        }

        if (!empty($data['difficulty'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.difficulty', ':difficulty'))
                ->setParameter('difficulty', $data['difficulty'])
            ;
        }

        if (!empty($data['comment'])) {
            $queryBuilder
                ->andWhere($queryBuilder->expr()->eq('ck.comment', ':comment'))
                ->setParameter('comment', $data['comment'])
            ;
        }

        if (!empty($data['deletedAt'])) {
            $queryBuilder

                ->andWhere($queryBuilder->expr()->between(
                    'ck.deletedAt',
                    ':deletedAtFrom',
                    ':deletedAtTo'
                ))
                ->setParameter('deletedAtFrom', $data['deletedAt'])
                ->setParameter('deletedAtTo', $data['deletedAt'])
            ;
        }

        if (!empty($data['updatedAt'])) {
            $queryBuilder

                ->andWhere($queryBuilder->expr()->between(
                    'ck.updatedAt',
                    ':updatedAtFrom',
                    ':updatedAtTo'
                ))
                ->setParameter('updatedAtFrom', $data['updatedAt'])
                ->setParameter('updatedAtTo', $data['updatedAt'])
            ;
        }

        if (!empty($data['createdAt'])) {
            $queryBuilder

                ->andWhere($queryBuilder->expr()->between(
                    'ck.createdAt',
                    ':createdAtFrom',
                    ':createdAtTo'
                ))
                ->setParameter('createdAtFrom', $data['createdAt'])
                ->setParameter('createdAtTo', $data['createdAt'])
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
