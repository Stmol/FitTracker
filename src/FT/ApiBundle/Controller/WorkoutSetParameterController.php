<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 28.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;


use FT\WorkoutBundle\Entity\Workout;
use FT\WorkoutBundle\Entity\WorkoutSet;
use FT\WorkoutBundle\Entity\WorkoutSetParameter;
use FT\WorkoutBundle\Form\Type\WorkoutSetParameterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class WorkoutSetParameterController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class WorkoutSetParameterController extends Controller
{
    /**
     * @Rest\View(statusCode=200)
     *
     * @param $workout_id
     * @param $set_id
     * @return \Doctrine\Common\Collections\Collection
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction($workout_id, $set_id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($set_id) {
                return $workoutSet->getId() == $set_id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        return $workoutSet->getWorkoutSetParameters()->filter(
            function ($workoutSetParameter) {
                return $workoutSetParameter->getIsRemoved() === false;
            }
        );
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @param $workout_id
     * @param $set_id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createAction(Request $request, $workout_id, $set_id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() and $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($set_id) {
                return $workoutSet->getId() == $set_id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $workoutSetParameter = $this->getWorkoutSetParameterManager()->createWorkoutSetParameter();

        $form = $this->createWorkoutSetParameterForm($workoutSetParameter);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $workoutSetParameter->setWorkoutSet($workoutSet);
        $this->getWorkoutSetParameterManager()->saveWorkoutSetParameter($workoutSetParameter);

        return;
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param $workout_id
     * @param $set_id
     * @param $id
     * @return \Doctrine\Common\Collections\Collection
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function readAction($workout_id, $set_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($set_id) {
                return $workoutSet->getId() == $set_id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $workoutSetParameter = $workoutSet->getWorkoutSetParameters()->filter(
            function ($workoutSetParameter) use ($id) {
                return $workoutSetParameter->getId() == $id and $workoutSetParameter->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSetParameter instanceof WorkoutSetParameter) {
            throw $this->createNotFoundException('Parameter not found');
        }

        return $workoutSetParameter;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param Request $request
     * @param $workout_id
     * @param $set_id
     * @param $id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $workout_id, $set_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() or $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($set_id) {
                return $workoutSet->getId() == $set_id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $workoutSetParameter = $workoutSet->getWorkoutSetParameters()->filter(
            function ($workoutSetParameter) use ($id) {
                return $workoutSetParameter->getId() == $id and $workoutSetParameter->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSetParameter instanceof WorkoutSetParameter) {
            throw $this->createNotFoundException('Parameter not found');
        }

        $form = $this->createWorkoutSetParameterForm($workoutSetParameter);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if (!$form->isValid()) {
            return $form;
        }

        if (!$workoutSetParameter->getWorkoutSet() instanceof WorkoutSet) {
            $workoutSetParameter->setWorkoutSet($workoutSet);
        }

        $this->getWorkoutSetParameterManager()->saveWorkoutSetParameter($workoutSetParameter);

        return;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param $workout_id
     * @param $set_id
     * @param $id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($workout_id, $set_id, $id)
    {
        $workout = $this->getWorkoutManager()->findWorkoutById($workout_id);

        if (!$workout instanceof Workout) {
            throw $this->createNotFoundException('Workout not found');
        }

        if (!$this->getUser() and $this->getUser() !== $workout->getUser()) {
            throw new AccessDeniedException;
        }

        $workoutSet = $workout->getWorkoutSets()->filter(
            function ($workoutSet) use ($set_id) {
                return $workoutSet->getId() == $set_id and $workoutSet->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSet instanceof WorkoutSet) {
            throw $this->createNotFoundException('Set not found');
        }

        $workoutSetParameter = $workoutSet->getWorkoutSetParameters()->filter(
            function ($workoutSetParameter) use ($id) {
                return $workoutSetParameter->getId() == $id and $workoutSetParameter->getIsRemoved() === false;
            }
        )->first();

        if (!$workoutSetParameter instanceof WorkoutSetParameter) {
            throw $this->createNotFoundException('Parameter not found');
        }

        $this->getWorkoutSetParameterManager()->deleteWorkoutSetParameter($workoutSetParameter);

        return;
    }

    /**
     * @return \FT\WorkoutBundle\Manager\WorkoutManager
     */
    private function getWorkoutManager()
    {
        return $this->get('ft_workout.manager.workout');
    }

    /**s
     * @return \FT\WorkoutBundle\Manager\WorkoutSetParameterManager
     */
    private function getWorkoutSetParameterManager()
    {
        return $this->get('ft_workout.manager.workout_set_parameter');
    }

    /**
     * @param WorkoutSetParameter $workoutSetParameter
     * @return \Symfony\Component\Form\Form
     */
    private function createWorkoutSetParameterForm(WorkoutSetParameter $workoutSetParameter)
    {
        $form = $this->createForm(new WorkoutSetParameterType(), $workoutSetParameter, [
            'csrf_protection'   => false,
            'validation_groups' => ['api'],
        ]);

        return $form;
    }
}
