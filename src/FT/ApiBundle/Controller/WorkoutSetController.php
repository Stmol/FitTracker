<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 28.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FT\WorkoutBundle\Entity\Workout;
use FT\WorkoutBundle\Entity\WorkoutSet;
use FT\WorkoutBundle\Form\Type\WorkoutSetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class WorkoutSetController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutSetController extends Controller
{
    /**
     * @Rest\View(statusCode=200)
     *
     * @param $workout_id
     * @return \Doctrine\Common\Collections\Collection
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction($workout_id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        return $workout->getWorkoutSets()->filter(
            function ($workoutSet) {
                return false === $workoutSet->getIsRemoved();
            }
        );
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @param $workout_id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createAction(Request $request, $workout_id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $this->getWorkoutSetManager()->createWorkoutSet();

        $form = $this->createWorkoutSetForm($workoutSet);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        if (!$workoutSet->getWorkout() instanceof Workout) {
            $workoutSet->setWorkout($workout);
        }

        $this->getWorkoutSetManager()->saveWorkoutSet($workoutSet);

        return;
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param $workout_id
     * @param $id
     * @return WorkoutSet
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function readAction($workout_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($id) {
                return $workoutSet->getId() == $id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        return $workoutSet;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @param $workout_id
     * @param $id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function updateAction(Request $request, $workout_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($id) {
                return $workoutSet->getId() == $id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $form = $this->createWorkoutSetForm($workoutSet);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if (!$form->isValid()) {
            return $form;
        }

        if (!$workoutSet->getWorkout() instanceof Workout) {
            $workoutSet->setWorkout($workout);
        }

        $this->getWorkoutSetManager()->saveWorkoutSet($workoutSet);

        return;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param $workout_id
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function deleteAction($workout_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($id) {
                return $workoutSet->getId() == $id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $this->getWorkoutSetManager()->deleteWorkoutSet($workoutSet);

        return;
    }

    /**
     * @param WorkoutSet $workoutSet
     * @return \Symfony\Component\Form\Form
     */
    private function createWorkoutSetForm(WorkoutSet $workoutSet)
    {
        $form = $this->createForm(new WorkoutSetType(), $workoutSet, [
            'csrf_protection'   => false,
            'validation_groups' => ['api'],
        ]);

        return $form;
    }

    /**
     * @return \FT\WorkoutBundle\Manager\WorkoutManager
     */
    private function getWorkoutManager()
    {
        return $this->get('ft_workout.manager.workout');
    }

    /**
     * @return \FT\WorkoutBundle\Manager\WorkoutSetManager
     */
    private function getWorkoutSetManager()
    {
        return $this->get('ft_workout.manager.workout_set');
    }
}
