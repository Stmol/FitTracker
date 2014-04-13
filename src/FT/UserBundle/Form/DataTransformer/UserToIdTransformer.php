<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 09.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Form\DataTransformer;

use FT\UserBundle\Entity\User;
use FT\UserBundle\Manager\UserManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class UserToIdTransformer
 * @package FT\UserBundle\Form\DataTransformer
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserToIdTransformer implements DataTransformerInterface
{
    /**
     * @var \FT\UserBundle\Manager\UserManager
     */
    private $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param mixed $user
     * @return integer|null
     */
    public function transform($user)
    {
        if (null === $user) {
            return null;
        }

        return $user->getId();
    }

    /**
     * @param integer $id
     * @return null|User
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        $user = $this->userManager->findUserById($id);

        if (!$user instanceof User) {
            return null;
        }

        return $user;
    }
}
