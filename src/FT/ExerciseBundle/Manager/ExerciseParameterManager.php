<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 17.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FT\AppBundle\Manager\EntityManagerInterface;

/**
 * Class ExerciseParameterManager
 * @package FT\ExerciseBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseParameterManager implements EntityManagerInterface
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
     * @return ExerciseParameter
     */
    public function create()
    {
        return new ExerciseParameter();
    }

    /**
     * @param $exerciseParameter
     * @param bool $flush
     */
    public function save($exerciseParameter, $flush = true)
    {
        $this->objectManager->persist($exerciseParameter);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $exerciseParameter
     * @param bool $flush
     */
    public function delete($exerciseParameter, $flush = true)
    {
        $this->objectManager->remove($exerciseParameter);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $id
     * @return object
     */
    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:ExerciseParameter')
            ->find($id);
    }

    /**
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getAllLimited($limit, $offset)
    {
        return $this->objectManager
            ->getRepository('FTExerciseBundle:ExerciseParameter')
            ->findAllLimited($limit, $offset);
    }
}
