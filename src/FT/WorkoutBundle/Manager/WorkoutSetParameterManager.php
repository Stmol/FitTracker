<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 28.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\WorkoutBundle\Manager;


use Doctrine\ORM\EntityManager;
use FT\WorkoutBundle\Entity\WorkoutSetParameter;

/**
 * Class WorkoutSetParameterManager
 * @package FT\WorkoutBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutSetParameterManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

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
     * @return WorkoutSetParameter
     */
    public function createWorkoutSetParameter()
    {
        $workoutSetParameter = new WorkoutSetParameter();

        return $workoutSetParameter;
    }

    /**
     * @param WorkoutSetParameter $workoutSetParameter
     * @param bool $flush
     */
    public function saveWorkoutSetParameter(WorkoutSetParameter $workoutSetParameter, $flush = true)
    {
        $this->entityManager->persist($workoutSetParameter);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param WorkoutSetParameter $workoutSetParameter
     * @param bool $flush
     */
    public function deleteWorkoutSetParameter(WorkoutSetParameter $workoutSetParameter, $flush = true)
    {
        $workoutSetParameter
            ->setIsRemoved(true)
            ->setRemovedAt(new \DateTime());

        $this->saveWorkoutSetParameter($workoutSetParameter, $flush);
    }
}
