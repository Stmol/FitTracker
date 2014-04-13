<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 09.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class FollowersLinkRepository
 * @package FT\UserBundle\Entity
 * @author Yury Smidovich <dev@stmol.me>
 */
class FollowersLinkRepository extends EntityRepository
{
    public function getFollowersLinkQueryBuilder()
    {
        return $this->createQueryBuilder('fl');
    }
}
