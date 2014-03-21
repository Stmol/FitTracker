<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 07.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\WorkoutBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FT\AppBundle\Manager\EntityManagerInterface;
use FT\WorkoutBundle\Entity\Workout;

/**
 * Class WorkoutManager
 * @package FT\WorkoutBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutManager implements EntityManagerInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return Workout
     */
    public function create()
    {
        return new Workout();
    }

    /**
     * @param $workout
     * @param  bool                      $flush
     * @throws \InvalidArgumentException
     */
    public function save($workout, $flush = true)
    {
        if (!$workout instanceof Workout) {
            throw new \InvalidArgumentException();
        }

        $this->objectManager->persist($workout);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $workout
     * @throws \InvalidArgumentException
     */
    public function delete($workout)
    {
        if (!$workout instanceof Workout) {
            throw new \InvalidArgumentException();
        }

        $workout->setIsEnabled(false);
        $this->save($workout);
    }

    /**
     * @param $id
     * @return Workout|null
     */
    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTWorkoutBundle:Workout')
            ->find($id);
    }

    /**
     * @param $limit
     * @param $offset
     */
    public function getAllLimited($limit, $offset)
    {
        return $this->objectManager
            ->getRepository('FTWorkoutBundle:Workout')
            ->findAllLimited($limit, $offset);
    }
}
