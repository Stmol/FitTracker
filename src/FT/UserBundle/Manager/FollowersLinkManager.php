<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 09.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use FT\UserBundle\Entity\FollowersLink;
use FT\UserBundle\Entity\User;

/**
 * Class FollowersLinkManager
 * @package FT\UserBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class FollowersLinkManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \FT\UserBundle\Entity\FollowersLinkRepository
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
     * @param User $follower
     * @param User $target
     * @return FollowersLink
     */
    public function createFollowersLink($follower, $target)
    {
        $followersLink = new FollowersLink();

        $followersLink
            ->setFollower($follower)
            ->setTarget($target);

        return $followersLink;
    }

    /**
     * Set rejected status
     *
     * @param FollowersLink $followersLink
     * @param $status
     * @param bool $flush
     */
    public function changeStatusFollowersLink(FollowersLink $followersLink, $status, $flush = true)
    {
        $followersLink
            ->setStatus($status);

        $this->saveFollowersLink($followersLink, $flush);
    }

    /**
     * @param FollowersLink $followersLink
     * @param bool $flush
     */
    public function saveFollowersLink(FollowersLink $followersLink, $flush = true)
    {
        $this->entityManager->persist($followersLink);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param User $follower
     * @param User $target
     * @return null|\FT\UserBundle\Entity\FollowersLink
     */
    public function findFollowersLinkByFollowers($follower, $target)
    {
        $queryBuilder = $this->repository->getFollowersLinkQueryBuilder();

        return $queryBuilder
            ->andWhere('fl.follower = :follower')
            ->andWhere('fl.target = :target')
            ->setParameter('follower', $follower)
            ->setParameter('target', $target)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $target
     * @return array
     */
    public function findFollowersLinksByTarget(User $target)
    {
        $queryBuilder = $this->repository->getFollowersLinkQueryBuilder();

        return $queryBuilder
            ->andWhere('fl.target = :target')
            ->andWhere('fl.status = :status')
            ->setParameter('target', $target)
            ->setParameter('status', FollowersLink::LINK_STATUS_ACCEPTED)
            ->getQuery()
            ->getResult();
    }
}
