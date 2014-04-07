<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 07.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package FT\UserBundle\Entity
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserRepository extends EntityRepository
{
    /**
     * Get QB for all users
     *
     * @param bool $isRemoved
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUsersQueryBuilder($isRemoved = false)
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if (false === $isRemoved) {
            $queryBuilder
                ->where('u.isRemoved = :isRemoved')
                ->setParameter('isRemoved', $isRemoved);
        }

        return $queryBuilder;
    }

    /**
     * @param $field
     * @param string $order
     * @param bool $isRemoved
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUsersQBOrderBy($field, $order = 'ASC', $isRemoved = false)
    {
        $queryBuilder = $this->getUsersQueryBuilder($isRemoved);

        $queryBuilder
            ->orderBy('u.'.$field, $order);

        return $queryBuilder;
    }
}
