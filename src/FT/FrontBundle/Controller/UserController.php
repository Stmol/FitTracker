<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 06.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\FrontBundle\Controller;

use FT\FrontBundle\Service\Paginator;
use FT\UserBundle\Entity\FollowersLink;
use FT\UserBundle\Entity\User;
use FT\UserBundle\Form\Type\FollowersLinkType;
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
    /** Limit for paginator */
    const USERS_PER_PAGE = 25;

    /**
     * List of all Users
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $paginator = new Paginator();

        $users = $paginator->paginate(
            $this->getUserManager()->getUserRepository()->getUsersQBOrderBy('username'),
            $request->query->get('page', 1),
            self::USERS_PER_PAGE
        );

        return $this->render('FTFrontBundle:User:index.html.twig', [
            'users'     => $users,
            'paginator' => $paginator,
        ]);
    }

    /**
     * Signin
     *
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
     * Signup
     *
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
     * Show public user profile
     *
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($username)
    {
        $user = $this->getUserManager()->findUserByUsername($username);

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        $followersLink = $this->getFollowersLinkManager()
            ->findFollowersLinkByFollowers($this->getUser(), $user);

        if (!$followersLink instanceof FollowersLink) {
            $followersLink = $this->getFollowersLinkManager()
                ->createFollowersLink($this->getUser(), $user);

            $form = $this->createFollowForm($followersLink);
        } else {
            switch ($followersLink->getStatus()) {
                case FollowersLink::LINK_STATUS_ACCEPTED:
                    $form = $this->createUnfollowForm($followersLink);
                    break;
                case FollowersLink::LINK_STATUS_REJECTED:
                    $form = $this->createFollowForm($followersLink);
                    break;
                default:
                    $form = $this->createUnfollowForm($followersLink);
                    break;
            }
        }

        $followers = $this->getFollowersLinkManager()
            ->findFollowersLinksByTarget($user);

        return $this->render('FTFrontBundle:User:show.html.twig', [
            'user'      => $user,
            'form'      => $form->createView(),
            'followers' => $followers,
        ]);
    }

    /**
     * @param Request $request
     * @param $username
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function followAction(Request $request, $username)
    {
        $target = $this->getUserManager()
            ->findUserByUsername($username);

        if (!$target instanceof User) {
            throw $this->createNotFoundException();
        }

        // TODO (Stmol) Think about how it can be improved
        if ($target === $this->getUser()) {
            return $this->redirect($this->generateUrl('users_show', ['username' => $target->getUsername()]));
        }

        $followersLink = $this->getFollowersLinkManager()
            ->findFollowersLinkByFollowers($this->getUser(), $target);

        if (!$followersLink instanceof FollowersLink) {
            $followersLink = $this->getFollowersLinkManager()
                ->createFollowersLink($this->getUser(), $target);
        }

        $form = $this->createFollowForm($followersLink);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getFollowersLinkManager()
                ->changeStatusFollowersLink($followersLink, FollowersLink::LINK_STATUS_ACCEPTED);

            return $this->redirect($this->generateUrl('users_show', ['username' => $target->getUsername()]));
        }

        return $this->render('FTFrontBundle:User:show.html.twig', [
            'user' => $target,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $username
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function unfollowAction(Request $request, $username)
    {
        $target = $this->getUserManager()
            ->findUserByUsername($username);

        if (!$target instanceof User) {
            throw $this->createNotFoundException();
        }

        $followersLink = $this->getFollowersLinkManager()
            ->findFollowersLinkByFollowers($this->getUser(), $target);

        if (!$followersLink instanceof FollowersLink) {
            throw $this->createNotFoundException();
        }

        $form = $this->createUnfollowForm($followersLink);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getFollowersLinkManager()
                ->changeStatusFollowersLink($followersLink, FollowersLink::LINK_STATUS_REJECTED);

            return $this->redirect($this->generateUrl('users_show', ['username' => $target->getUsername()]));
        }

        return $this->render('FTFrontBundle:User:show.html.twig', [
            'user' => $target,
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
     * @param FollowersLink $followersLink
     * @return \Symfony\Component\Form\Form
     */
    private function createFollowForm(FollowersLink $followersLink)
    {
        $form = $this->createForm(new FollowersLinkType(), $followersLink, [
            'method' => 'POST',
            'action' => $this->generateUrl('users_follow', ['username' => $followersLink->getTarget()->getUsername()]),
        ]);

        $form
            ->add('follow', 'submit', [
                'label' => 'form.button.follow',
                'attr'  => array('class' => 'btn btn-primary'),
            ])
        ;

        return $form;
    }

    /**
     * @param FollowersLink $followersLink
     * @return \Symfony\Component\Form\Form
     */
    private function createUnfollowForm(FollowersLink $followersLink)
    {
        $form = $this->createForm(new FollowersLinkType(), $followersLink, [
            'method' => 'PATCH',
            'action' => $this->generateUrl('users_unfollow', ['username' => $followersLink->getTarget()->getUsername()]),
        ]);

        $form
            ->add('unfollow', 'submit', [
                'label' => 'form.button.unfollow',
                'attr'  => array('class' => 'btn btn-primary'),
            ])
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

    /**
     * @return \FT\UserBundle\Manager\FollowersLinkManager
     */
    private function getFollowersLinkManager()
    {
        return $this->get('ft_user.manager.followers_link');
    }
}
