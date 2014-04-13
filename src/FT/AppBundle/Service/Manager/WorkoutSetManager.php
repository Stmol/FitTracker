<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 28.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\AppBundle\Service\Manager;

use Doctrine\ORM\EntityManager;
use FT\AppBundle\Entity\Workout;
use FT\AppBundle\Entity\WorkoutSet;

/**
 * Class WorkoutSetManager
 * @package FT\WorkoutBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutSetManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
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
     * @param Workout $workout
     * @return WorkoutSet
     */
    public function createWorkoutSet(Workout $workout = null)
    {
        $workoutSet = new WorkoutSet();

        if ($workout instanceof Workout) {
            $workoutSet->setWorkout($workout);
        }

        return $workoutSet;
    }

    /**
     * @param WorkoutSet $workoutSet
     * @param bool $flush
     */
    public function saveWorkoutSet(WorkoutSet $workoutSet, $flush = true)
    {
        $this->entityManager->persist($workoutSet);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param WorkoutSet $workoutSet
     * @param bool $flush
     */
    public function deleteWorkoutSet(WorkoutSet $workoutSet, $flush = true)
    {
        $workoutSet
            ->setIsRemoved(true)
            ->setRemovedAt(new \DateTime());

        $this->saveWorkoutSet($workoutSet, $flush);
    }

    /**
     * @param $id
     * @param bool $isRemoved
     * @return WorkoutSet|null
     */
    public function findWorkoutSetById($id, $isRemoved = false)
    {
        return $this->repository
            ->createQueryBuilder('ws')
            ->where('ws.id = :id')
            ->andWhere('ws.isRemoved = :isRemoved')
            ->setParameter('id', $id)
            ->setParameter('isRemoved', $isRemoved)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param null $limit
     * @param null $offset
     * @param bool $isRemoved
     * @return array
     */
    public function findWorkoutSetsLimited($limit = null, $offset = null, $isRemoved = false)
    {
        $queryBuilder = $this->repository
            ->createQueryBuilder('ws')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (false === $isRemoved) {
            $queryBuilder
                ->where('ws.isRemoved = :isRemoved')
                ->setParameter('isRemoved', $isRemoved);
        }

        return $queryBuilder
            ->getQuery()
            ->execute();
    }
}