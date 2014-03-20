<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 20.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use FT\AppBundle\Manager\EntityManagerInterface;
use FT\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * Class UserManager
 * @package FT\UserBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserManager implements EntityManagerInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * @param ObjectManager $objectManager
     * @param EncoderFactory $encoderFactory
     */
    function __construct(ObjectManager $objectManager, EncoderFactory $encoderFactory)
    {
        $this->objectManager = $objectManager;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return User
     */
    public function create()
    {
        return new User();
    }

    /**
     * @param $user
     * @param bool $flush
     */
    public function save($user, $flush = true)
    {
        $this->updatePassword($user);
        $this->objectManager->persist($user);

        if ($flush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param $entity
     */
    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param User $user
     */
    private function updatePassword(User $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    /**
     * @param $id
     * @return User
     */
    public function getOneById($id)
    {
        return $this->objectManager
            ->getRepository('FTUserBundle:User')
            ->find($id);
    }

    /**
     * @param $limit
     * @param $offset
     */
    public function getAllLimited($limit, $offset)
    {
        return $this->objectManager
            ->getRepository('FTUserBundle:User')
            ->findAllLimited($limit, $offset);
    }
}