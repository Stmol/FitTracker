<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 07.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\FrontBundle\Service;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * Class Paginator
 * @package FT\FrontBundle\Service
 * @author Yury Smidovich <dev@stmol.me>
 */
class Paginator
{
    /**
     * @var integer
     */
    private $count;

    /**
     * @var integer
     */
    private $currentPage;

    /**
     * @var integer
     */
    private $totalPages;

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return mixed
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param \Doctrine\ORM\NativeQuery|\Doctrine\ORM\QueryBuilder $query
     * @param int $page
     * @param $limit
     */
    public function paginate($query, $page = 1, $limit)
    {
        $this->currentPage = $page;
        $limit = (int)$limit;

        if (is_a($query, '\Doctrine\ORM\NativeQuery')) {
            $sqlInitial = $query->getSQL();

            $rsm = new ResultSetMappingBuilder($query->getEntityManager());
            $rsm->addScalarResult('count', 'count');

            $sqlCount = 'select count(*) as count from (' . $sqlInitial . ') as item';
            $qCount = $query->getEntityManager()->createNativeQuery($sqlCount, $rsm);
            $qCount->setParameters($query->getParameters());

            $this->count = (int)$qCount->getSingleScalarResult();

            $query->setSQL($query->getSQL() . ' limit ' . (($page - 1) * $limit) . ', ' . $limit);
        } elseif(is_a($query, '\Doctrine\ORM\QueryBuilder')) {
            $query = $query
                ->setFirstResult(($page -1) * $limit)
                ->setMaxResults($limit)
                ->getQuery();

            $paginator = new DoctrinePaginator($query, $fetchJoinCollection = true);
            $this->count = count($paginator);
        }

        $this->totalPages = ceil($this->count / $limit);

        return $query->getResult();
    }
}