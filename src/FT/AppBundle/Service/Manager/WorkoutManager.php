<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 07.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\AppBundle\Service\Manager;

use Doctrine\ORM\EntityManager;
use FT\UserBundle\Entity\User;
use FT\AppBundle\Entity\Workout;

/**
 * Class WorkoutManager
 * @package FT\WorkoutBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutManager
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
     * @param \FT\UserBundle\Entity\User $user
     * @return Workout
     */
    public function createWorkout(User $user = null)
    {
        $workout = new Workout();

        if ($user instanceof User) {
            $workout->setUser($user);
        }

        return $workout;
    }

    /**
     * @param $workout
     * @param  bool                      $flush
     * @throws \InvalidArgumentException
     */
    public function saveWorkout(Workout $workout, $flush = true)
    {
        $this->entityManager->persist($workout);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param \FT\WorkoutBundle\Entity\Workout $workout
     * @param bool $flush
     */
    public function deleteWorkout(Workout $workout, $flush = true)
    {
        $workout
            ->setRemovedAt(new \DateTime())
            ->setIsRemoved(true);

        $this->saveWorkout($workout, $flush);
    }

    /**
     * Find single Workout by ID
     *
     * @param $id
     * @param bool $isRemoved
     * @return null|Workout
     */
    public function findWorkoutById($id, $isRemoved = false)
    {
        return $this->repository
            ->createQueryBuilder('w')
            ->where('w.id = :id')
            ->andWhere('w.isRemoved = :isRemoved')
            ->setParameter('id', $id)
            ->setParameter('isRemoved', $isRemoved)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find all Workouts limited
     *
     * @param bool $isRemoved
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findWorkoutsLimited($isRemoved = false, $limit = null, $offset = null)
    {
        $limit = !empty($limit) ? $limit : 100;
        $offset = $offset ? $offset : 0;

        $queryBuilder = $this->repository
            ->createQueryBuilder('w')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (false === $isRemoved) {
            $queryBuilder
                ->where('w.isRemoved = :isRemoved')
                ->setParameter('isRemoved', $isRemoved);
        }

        return $queryBuilder
            ->getQuery()
            ->execute();
    }
}
