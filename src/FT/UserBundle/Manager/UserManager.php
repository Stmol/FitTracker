<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 20.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Manager;

use Doctrine\ORM\EntityManager;
use FT\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserManager
 * @package FT\UserBundle\Manager
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * @var \FT\UserBundle\Entity\UserRepository
     */
    private $repository;

    /**
     * @param EntityManager $entityManager
     * @param EncoderFactory $encoderFactory
     * @param $className
     */
    public function __construct(EntityManager $entityManager, EncoderFactory $encoderFactory, $className)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->repository = $entityManager->getRepository($className);
    }

    /**
     * @return \FT\UserBundle\Entity\UserRepository
     */
    public function getUserRepository()
    {
        return $this->repository;
    }

    /**
     * @return User
     */
    public function createUser()
    {
        return new User();
    }

    /**
     * @param $user
     * @param bool $flush
     */
    public function saveUser(User $user, $flush = true)
    {
        $this->updatePassword($user);
        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param \FT\UserBundle\Entity\User $user
     * @param bool $flush
     */
    public function deleteUser(User $user, $flush = true)
    {
        $user
            ->setIsRemoved(true)
            ->setRemovedAt(new \DateTime());

        $this->saveUser($user, $flush);
    }

    /**
     * @param User $user
     */
    private function updatePassword(User $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $user->setPassword($this->encodePassword($user, $password));
            $user->eraseCredentials();
        }
    }

    /**
     * @param $id
     * @return User
     */
    public function findUserById($id)
    {
        return $this->repository
            ->createQueryBuilder('u')
            ->where('u.isRemoved = :isRemoved')
            ->andWhere('u.id = :id')
            ->setParameter('isRemoved', false)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $username
     * @param bool $isRemoved
     * @return null|\FT\UserBundle\Entity\User
     */
    public function findUserByUsername($username, $isRemoved = false)
    {
        $queryBuilder = $this->repository
            ->getUsersQueryBuilder($isRemoved);

        return $queryBuilder
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $limit
     * @param $offset
     * @return array|null
     */
    public function findUsersLimited($limit = null, $offset = null)
    {
        return $this->repository
            ->createQueryBuilder('u')
            ->where('u.isRemoved = :isRemoved')
            ->setParameter('isRemoved', false)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->execute();
    }

    /**
     * Get User by username and password
     *
     * @param $username
     * @param $password
     * @return User|null
     */
    public function findUserByCredentials($username, $password)
    {
        /** @var User $user */
        $user = $this->repository
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.isRemoved = :isRemoved')
            ->setParameter('username', $username)
            ->setParameter('isRemoved', false)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user instanceof User) {
            return null;
        }

        $encodedPassword = $this->encodePassword($user, $password);

        if ($user->getPassword() !== $encodedPassword) {
            return null;
        }

        return $user;
    }

    /**
     * Encode password
     *
     * @param  UserInterface $user
     * @param $password
     * @return string
     */
    public function encodePassword(UserInterface $user, $password)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->encodePassword($password, $user->getSalt());
    }
}
