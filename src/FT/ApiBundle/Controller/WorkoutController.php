<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 13.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use FT\WorkoutBundle\Entity\Workout;
use FT\WorkoutBundle\Form\Type\WorkoutType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class WorkoutController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutController extends Controller
{
    /**
     * @Rest\View(statusCode=200)
     *
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit', 50);
        $offset = $request->query->get('offset', 0);
        $isRemoved = $request->query->has('is_removed');

        return $this->getWorkoutManager()
            ->findWorkoutsLimited($isRemoved, $limit, $offset);
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @return \Symfony\Component\Form\Form
     */
    public function createAction(Request $request)
    {
        $workout = $this->getWorkoutManager()
            ->createWorkout($this->getUser());

        $form = $this->createWorkoutForm($workout);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getWorkoutManager()->saveWorkout($workout);

        return;
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Workout
     */
    public function readAction(Request $request, $id)
    {
        $workout = $this->getWorkoutManager()
            ->findWorkoutById($id, $request->query->has('is_removed'));

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        return $workout;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->createWorkoutForm($workout);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getWorkoutManager()->saveWorkout($workout);

        return;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param $id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException();
        }

        $this->getWorkoutManager()->deleteWorkout($workout);

        return;
    }

    /**
     * @param  Workout                      $workout
     * @return \Symfony\Component\Form\Form
     */
    protected function createWorkoutForm(Workout $workout)
    {
        $form = $this->createForm(new WorkoutType(), $workout, [
            'csrf_protection' => false,
        ]);

        return $form;
    }

    /**
     * Get manager for entity
     *
     * @return \FT\WorkoutBundle\Manager\WorkoutManager
     */
    protected function getWorkoutManager()
    {
        return $this->get('ft_workout.manager.workout');
    }
}
