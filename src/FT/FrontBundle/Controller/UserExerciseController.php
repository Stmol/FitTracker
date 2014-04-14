<?php

namespace FT\FrontBundle\Controller;

use FT\FrontBundle\Service\Paginator;
use FT\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Routing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserExerciseController
 * @package FT\FrontBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 *
 * @Routing\Route("/users/{username}")
 */
class UserExerciseController extends Controller
{
    const EXERCISE_PER_PAGE = 20;

    /**
     * @Routing\Route("/exercises", name="user_exercise_index")
     * @Routing\Method("GET")
     * @Routing\Template()
     */
    public function indexAction(Request $request, $username)
    {
        $user = $this->getUserManager()->findUserByUsername($username);

        if (!$user instanceof User) {
            throw $this->createNotFoundException();
        }

        $paginator = new Paginator();

        $exercises = $paginator->paginate(
            $this->getExerciseManager()->getExerciseRepository()->getExerciseQBByUser($user),
            $request->query->get('page', 1),
            self::EXERCISE_PER_PAGE
        );

        return [
            'paginator' => $paginator,
            'exercises' => $exercises,
            'user'      => $user,
        ];
    }

    /**
     * @return \FT\UserBundle\Manager\UserManager
     */
    private function getUserManager()
    {
        return $this->get('ft_user.manager.user');
    }

    /**
     * @return \FT\AppBundle\Service\Manager\ExerciseManager
     */
    private function getExerciseManager()
    {
        return $this->get('ft_app.manager.exercise');
    }
}
