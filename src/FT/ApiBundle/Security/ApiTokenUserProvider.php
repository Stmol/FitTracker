<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 20.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Security;

use Doctrine\Common\Persistence\ObjectManager;
use FT\ApiBundle\Entity\ApiToken;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiTokenUserProvider
 * @package FT\ApiBundle\Security
 * @author Yury Smidovich <dev@stmol.me>
 */
class ApiTokenUserProvider implements UserProviderInterface
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

    public function getUsernameByToken($tokenString)
    {
        /** @var \FT\ApiBundle\Entity\ApiToken $apiToken */
        $apiToken = $this->objectManager
            ->getRepository('FTApiBundle:ApiToken')
            ->findOneByTokenString($tokenString);

        if (!$apiToken instanceof ApiToken) {
            return null;
        }

        $user = $apiToken->getUser();

        if (!$user) {
            return null;
        }

        return $user->getUsername();
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        return $this->objectManager
            ->getRepository('FTUserBundle:User')
            ->findOneBy(['username' => $username]);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return 'FT\UserBundle\Entity\User' === $class;
    }
}
