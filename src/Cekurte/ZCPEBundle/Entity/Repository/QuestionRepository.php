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

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilderNumberOfResults()
    {
        return $this->createQueryBuilder('ck')
            ->select('COUNT(ck.id) AS numberOfResults')
        ;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getQueryBuilderNumberOfResultsBy($field, $value)
    {
        $queryBuilder = $this->getQueryBuilderNumberOfResults();

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->eq(sprintf('ck.%s', $field), sprintf(':%s', $field)))
            ->setParameter($field, $value)
        ;
    }

    /**
     * Get total
     *
     * @return mixed
     */
    public function getTotal()
    {
        return $this->getQueryBuilderNumberOfResults()
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd revised
     *
     * @return mixed
     */
    public function getQtdRevised()
    {
        return $this->getQueryBuilderNumberOfResultsBy('revised', true)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd approved
     *
     * @return mixed
     */
    public function getQtdApproved()
    {
        return $this->getQueryBuilderNumberOfResultsBy('approved', true)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd email was sent
     *
     * @return mixed
     */
    public function getQtdEmailWasSent()
    {
        return $this->getQueryBuilderNumberOfResultsBy('emailHasSent', true)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd imported from google groups
     *
     * @return mixed
     */
    public function getQtdImportedFromGoogleGroups()
    {
        return $this->getQueryBuilderNumberOfResultsBy('importedFromGoogleGroups', true)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd type single choice
     *
     * @return mixed
     */
    public function getQtdTypeSingleChoice()
    {
        return $this->getQueryBuilderNumberOfResultsBy('questionType', 1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd type multiple choice
     *
     * @return mixed
     */
    public function getQtdTypeMultipleChoice()
    {
        return $this->getQueryBuilderNumberOfResultsBy('questionType', 2)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Get qtd type text
     *
     * @return mixed
     */
    public function getQtdTypeText()
    {
        return $this->getQueryBuilderNumberOfResultsBy('questionType', 3)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param int $numberOfResults
     * @return mixed
     */
    public function getCategoriesOrderedByNumberOfQuestions($numberOfResults = 10)
    {
        return $this->createQueryBuilder('ck')
            ->select('COUNT(ck.id) AS questions')
            ->addSelect('c.title AS name')
            ->innerJoin('ck.category', 'c')
            ->groupBy('c.id')
            ->orderBy('c.title', 'asc')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
