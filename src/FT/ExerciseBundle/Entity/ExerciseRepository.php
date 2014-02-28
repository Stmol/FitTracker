<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ExerciseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExerciseRepository extends EntityRepository
{
    public function findAllLimited($limit, $offset)
    {
        $dql = 'SELECT e FROM FTExerciseBundle:Exercise e';

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getResult();
    }

    public function findOneById($id)
    {
        $dql = 'SELECT e FROM FTExerciseBundle:Exercise e
                WHERE e.id = :id';

        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }
}
