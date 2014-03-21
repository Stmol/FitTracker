<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 21.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FT\ApiBundle\Entity\ApiToken;
use FT\AppBundle\Manager\EntityManagerInterface;
use FT\UserBundle\Entity\User;

/**
 * Class ApiTokenManager
 * @package FT\ApiBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class ApiTokenManager implements EntityManagerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param  \FT\UserBundle\Entity\User $user
     * @return ApiToken
     */
    public function create(User $user = null)
    {
        $apiToken = new ApiToken();

        if ($user) {
            $apiToken->setUser($user);
        }

        return $apiToken;
    }

    /**
     * @param $apiToken
     * @param bool $flush
     */
    public function save($apiToken, $flush = true)
    {
        $this->objectManager->persist($apiToken);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $apiToken
     * @param bool $flush
     */
    public function delete($apiToken, $flush = true)
    {
        $this->objectManager->remove($apiToken);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $id
     * @return ApiToken
     */
    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTApiBundle:ApiToken')
            ->find($id);
    }

    /**
     * Get ApiToken by token string
     *
     * @param $token
     * @return mixed
     */
    public function getOneByToken($token)
    {
        return $this->objectManager
            ->getRepository('FTApiBundle:ApiToken')
            ->findOneByTokenString($token);
    }

    /**
     * @param $limit
     * @param $offset
     */
    public function getAllLimited($limit, $offset)
    {
        // TODO: Implement getAllLimited() method.
    }

    /**
     * @param $user
     * @return array
     */
    public function getAllByUser($user)
    {
        return $this->objectManager
            ->getRepository('FTApiBundle:ApiToken')
            ->findAllByUser($user);
    }
}
