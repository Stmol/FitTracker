<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 06.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\FrontBundle\Controller;

use FT\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FT\UserBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class UserController
 * @package FT\FrontBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $users = $this->getUserManager()->findUsersLimited(50, 0);

        return $this->render('FTFrontBundle:User:index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signinAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('FTFrontBundle:User:signin.html.twig', [
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signupAction()
    {
        $user = $this->getUserManager()->createUser();
        $form = $this->createUserForm($user);

        return $this->render('FTFrontBundle:User:signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $user = $this->getUserManager()->createUser();

        $form = $this->createUserForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getUserManager()->saveUser($user);

            return $this->redirect($this->generateUrl('users_sign_in'));
        }

        return $this->render('FTFrontBundle:User:signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param User $user
     * @return \Symfony\Component\Form\Form
     */
    private function createUserForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('users_create'),
        ]);

        $form
            ->add('agreement', 'checkbox', [
                'label'    => 'user.signup.agreement',
                'required' => true,
                'mapped'   => false,
            ])
            ->add('signup', 'submit', ['label' => 'user.signup.signup'])
        ;

        return $form;
    }

    /**
     * @return \FT\UserBundle\Manager\UserManager
     */
    private function getUserManager()
    {
        return $this->get('ft_user.manager.user');
    }
}
