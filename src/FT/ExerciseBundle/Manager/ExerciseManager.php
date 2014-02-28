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
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Exercise
     */
    public function createExercise()
    {
        $exercise = new Exercise();

        return $exercise;
    }

    /**
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

    public function getExercise($id)
    {
        return $this->entityManager
            ->getRepository('FTExerciseBundle:Exercise')
            ->findOneById($id);
    }

    public function getExercises($limit = 10, $offset = 0)
    {
        return $this->entityManager
            ->getRepository('FTExerciseBundle:Exercise')
            ->findAllLimited($limit, $offset);
    }
}
