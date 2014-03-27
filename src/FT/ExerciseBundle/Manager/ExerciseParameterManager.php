<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 17.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

use Doctrine\ORM\EntityManager;
use FT\ExerciseBundle\Entity\Exercise;
use FT\ExerciseBundle\Entity\ExerciseParameter;

/**
 * Class ExerciseParameterManager
 * @package FT\ExerciseBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseParameterManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     * @param $className
     */
    public function __construct(EntityManager $entityManager, $className)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($className);
    }

    /**
     * @param  \FT\ExerciseBundle\Entity\Exercise $exercise
     * @return ExerciseParameter
     */
    public function createExerciseParameter(Exercise $exercise = null)
    {
        $exerciseParameter = new ExerciseParameter();

        if ($exercise instanceof Exercise) {
            $exerciseParameter->setExercise($exercise);
        }

        return $exerciseParameter;
    }

    /**
     * @param ExerciseParameter $exerciseParameter
     * @param bool              $flush
     */
    public function saveExerciseParameter(ExerciseParameter $exerciseParameter, $flush = true)
    {
        $this->entityManager->persist($exerciseParameter);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param $exerciseParameter
     * @param bool $flush
     */
    public function deleteExerciseParameter(ExerciseParameter $exerciseParameter, $flush = true)
    {
        $exerciseParameter
            ->setRemovedAt(new \DateTime())
            ->setIsRemoved(true);

        $this->saveExerciseParameter($exerciseParameter, $flush);
    }

    /**
     * Find one by ID
     *
     * @param $id
     * @return ExerciseParameter|null
     */
    public function findExerciseParameterById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param  bool  $isRemoved
     * @param  null  $limit
     * @param  null  $offset
     * @return mixed
     */
    public function findExerciseParametersLimited($isRemoved = false, $limit = null, $offset = null)
    {
        $limit = !empty($limit) ? $limit : 100;
        $offset = $offset ? $offset : 0;

        $queryBuilder = $this->repository
            ->createQueryBuilder('ep')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (false === $isRemoved) {
            $queryBuilder
                ->where('ep.isRemoved = :isRemoved')
                ->setParameter(':isRemoved', $isRemoved);
        }

        return $queryBuilder
            ->getQuery()
            ->execute();
    }

    /**
     * @param  Exercise $exercise
     * @param  bool     $isRemoved
     * @return array
     */
    public function findExerciseParametersByExercise(Exercise $exercise, $isRemoved = false)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('ep')
            ->where('ep.exercise = :exercise')
            ->setParameter('exercise', $exercise);

        if (false === $isRemoved) {
            $queryBuilder
                ->andWhere('ep.isRemoved = :isRemoved')
                ->setParameter(':isRemoved', $isRemoved);
        }

        return $queryBuilder
            ->getQuery()
            ->execute();
    }
}
