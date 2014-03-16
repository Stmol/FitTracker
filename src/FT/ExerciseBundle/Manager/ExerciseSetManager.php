<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 10.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use FT\AppBundle\Manager\EntityManagerInterface;
use FT\ExerciseBundle\Entity\ExerciseSet;
use FT\WorkoutBundle\Entity\Workout;

/**
 * Class ExerciseSetManager
 * @package FT\ExerciseBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseSetManager implements EntityManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return ExerciseSet
     */
    public function create()
    {
        return new ExerciseSet();
    }

    /**
     * @param $exerciseSet
     * @param bool $flush
     */
    public function save($exerciseSet, $flush = true)
    {
        $this->objectManager->persist($exerciseSet);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $exerciseSet
     * @param bool $flush
     */
    public function delete($exerciseSet, $flush = true)
    {
        $this->objectManager->remove($exerciseSet);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $id
     * @return ExerciseSet
     */
    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:ExerciseSet')
            ->find($id);
    }

    /**
     * @param $limit
     * @param $offset
     * @return ArrayCollection
     */
    public function getAllLimited($limit, $offset)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:ExerciseSet')
            ->findAllLimited($limit, $offset);
    }

    public function getAllByWorkout(Workout $workout)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:ExerciseSet')
            ->findAllByWorkout($workout);
    }
}
