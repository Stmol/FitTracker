<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 20.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FT\UserBundle\Entity\User;
use FT\UserBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserController extends FOSRestController
{
    /**
     * @Rest\View()
     *
     * @param  Request                   $request
     * @return \FOS\RestBundle\View\View
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit', 50);
        $offset = $request->query->get('offset', 0);

        $users = $this->getEntityManager()
            ->findUsersLimited($limit, $offset);

        return $users;
    }

    /**
     * @Rest\View(statusCode=201)
     *
     * @param  \Symfony\Component\HttpFoundation\Request               $request
     * @return \FT\UserBundle\Entity\User|\Symfony\Component\Form\Form
     */
    public function createAction(Request $request)
    {
        $user = $this->getEntityManager()->createUser();
        $form = $this->createUserForm($user);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->getEntityManager()->saveUser($user);

            return $user;
        }

        return $form;
    }

    /**
     * @Rest\View()
     *
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \FT\UserBundle\Entity\User
     */
    public function readAction($id)
    {
        $user = $this->getEntityManager()->findUserById($id);

        if (!$user) {
            throw $this->createNotFoundException();
        }

        return $user;
    }

    /**
     * @param  User                         $user
     * @return \Symfony\Component\Form\Form
     */
    private function createUserForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, [
            'csrf_protection' => false,
        ]);

        return $form;
    }

    /**
     * Get manager for entity
     *
     * @return \FT\UserBundle\Manager\UserManager
     */
    private function getEntityManager()
    {
        return $this->get('ft_user.manager.user');
    }
}
