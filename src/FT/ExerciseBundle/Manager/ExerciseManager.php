<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 23.02.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FT\ExerciseBundle\Entity\Exercise;

/**
 * Class ExerciseManager
 * @package FT\ExerciseBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseManager implements EntityManagerInterface
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

    public function create()
    {
        return new Exercise();
    }

    public function save($exercise, $flush = true)
    {
        $this->objectManager->persist($exercise);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    public function delete($exercise)
    {
        if (!$exercise instanceof Exercise) {
            throw new \InvalidArgumentException('Entity object must be instance of Exercise');
        }

        $exercise->setIsEnabled(false);
        $this->save($exercise);
    }

    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:Exercise')
            ->find($id);
    }

    public function getAllLimited($limit, $offset)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:Exercise')
            ->findAllLimited($limit, $offset);
    }
}
