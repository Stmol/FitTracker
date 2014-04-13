<?php

namespace FT\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FT\UserBundle\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueIdx;

/**
 * FollowersLink
 *
 * @ORM\Table(name="followers", uniqueConstraints={@UniqueIdx(
 *      name="followers_link_idx",
 *      columns={"follower_id", "target_id"})}
 * )
 * @ORM\Entity(repositoryClass="FT\UserBundle\Entity\FollowersLinkRepository")
 *
 * @UniqueEntity(
 *     fields={"follower", "target"},
 *     message="You already follow this user"
 * )
 */
class FollowersLink
{
    const
        LINK_STATUS_REJECTED  = 0,
        LINK_STATUS_ACCEPTED  = 1,
        LINK_STATUS_REQUESTED = 2
    ;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="FT\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id", nullable=false)
     */
    private $follower;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="FT\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="target_id", referencedColumnName="id", nullable=false)
     */
    private $target;

    public function __construct()
    {
        $this->status = self::LINK_STATUS_ACCEPTED;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return FollowersLink
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set follower
     *
     * @param User $follower
     * @return FollowersLink
     */
    public function setFollower(User $follower = null)
    {
        $this->follower = $follower;

        return $this;
    }

    /**
     * Get follower
     *
     * @return User
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Set target
     *
     * @param User $target
     * @return FollowersLink
     */
    public function setTarget(User $target = null)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return User
     */
    public function getTarget()
    {
        return $this->target;
    }
}
