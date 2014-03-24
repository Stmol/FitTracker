<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 23.02.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

use Doctrine\ORM\EntityManager;
use FT\ExerciseBundle\Entity\Exercise;
use FT\UserBundle\Entity\User;

/**
 * Class ExerciseManager
 * @package FT\ExerciseBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseManager
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
     * Create new Exercise
     *
     * @param \FT\UserBundle\Entity\User $user
     * @return Exercise
     */
    public function createExercise(User $user = null)
    {
        $exercise = new Exercise();

        if ($user) {
            $exercise->setUser($user);
        }

        return $exercise;
    }

    /**
     * Persist Exercise entity
     *
     * @param Exercise $exercise
     * @param bool $flush
     */
    public function saveExercise(Exercise $exercise, $flush = true)
    {
        $this->entityManager->persist($exercise);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * Delete Exercise
     *
     * @param Exercise $exercise
     */
    public function deleteExercise(Exercise $exercise)
    {
        $exercise
            ->setRemovedAt(new \DateTime())
            ->setIsRemoved(true);

        $this->saveExercise($exercise);
    }

    /**
     * Find one Exercise by ID
     *
     * @param $id
     * @param bool $isRemoved
     * @return Exercise|null
     */
    public function findExerciseById($id, $isRemoved = false)
    {
        return $this->repository
            ->createQueryBuilder('e')
            ->where('e.isRemoved = :isRemoved')
            ->andWhere('e.id = :id')
            ->setParameter('isRemoved', $isRemoved)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find all Exercises by limit and offset
     *
     * @param bool $isRemoved
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function findExercisesLimited($isRemoved = false, $limit = null, $offset = null)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('e')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (false === $isRemoved) {
            $queryBuilder
                ->where('e.isRemoved = :isRemoved')
                ->setParameter(':isRemoved', $isRemoved);
        }

        return $queryBuilder
            ->getQuery()
            ->execute();
    }

    public function findExercisesByIds()
    {

    }
}
