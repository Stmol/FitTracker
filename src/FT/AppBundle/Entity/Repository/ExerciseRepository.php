<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 13.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ExerciseRepository
 * @package FT\AppBundle\Entity\Repository
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseRepository extends EntityRepository
{
    /**
     * @param bool $isRemoved
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getExerciseQueryBuilder($isRemoved = false)
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if (false === $isRemoved) {
            $queryBuilder
                ->andWhere('e.isRemoved = :isRemoved')
                ->setParameter('isRemoved', $isRemoved);
        }

        return $queryBuilder;
    }

    /**
     * @param $user
     * @param bool $isRemoved
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getExerciseQBByUser($user, $isRemoved = false)
    {
        $queryBuilder = $this->getExerciseQueryBuilder($isRemoved);

        $queryBuilder
            ->andWhere('e.user = :user')
            ->setParameter('user', $user);

        return $queryBuilder;
    }
}
