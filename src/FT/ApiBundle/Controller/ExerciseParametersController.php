<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 27.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use FT\ExerciseBundle\Entity\Exercise;
use FT\ExerciseBundle\Entity\ExerciseParameter;
use FT\ExerciseBundle\Form\Type\ExerciseParameterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ExerciseParametersController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseParametersController extends Controller
{
    /**
     * @Rest\View(statusCode=200)
     *
     * @param  Request                                                       $request
     * @param $id
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction(Request $request, $id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $exerciseParameters = $this->getExerciseParameterManager()
            ->findExerciseParametersByExercise(
                $exercise,
                $request->query->has('is_removed')
            );

        return $exerciseParameters;
    }

    /**
     * @Rest\View(statusCode=200)
     *
     * @param $exercise_id
     * @param $id
     * @return ExerciseParameter
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function readAction($exercise_id, $id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($exercise_id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $exerciseParameter = $exercise->getExerciseParameters()->filter(
            function ($exerciseParameter) use ($id) {
                return $exerciseParameter->getId() == $id;
            }
        )->first();

        if (!$exerciseParameter instanceof ExerciseParameter) {
            throw $this->createNotFoundException('Exercise parameter not found');
        }

        return $exerciseParameter;
    }

    /**
     * @Rest\View(statusCode=201)
     * @Security("has_role('ROLE_API')")
     *
     * @param  Request                                                          $request
     * @param $exercise_id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\Form\Form|null
     */
    public function createAction(Request $request, $exercise_id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($exercise_id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        if (!$this->getUser() or $this->getUser() !== $exercise->getUser()) {
            throw new AccessDeniedException();
        }

        $exerciseParameter = $this->getExerciseParameterManager()
            ->createExerciseParameter($exercise);

        $form = $this->createExerciseParameterForm($exerciseParameter);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getExerciseParameterManager()
            ->saveExerciseParameter($exerciseParameter);

        return;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param  Request                                                          $request
     * @param $exercise_id
     * @param $id
     * @return \Symfony\Component\Form\Form
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updateAction(Request $request, $exercise_id, $id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($exercise_id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        if (!$this->getUser() or $this->getUser() !== $exercise->getUser()) {
            throw new AccessDeniedException();
        }

        $exerciseParameter = $exercise->getExerciseParameters()->filter(
            function ($exerciseParameter) use ($id) {
                return $exerciseParameter->getId() == $id;
            }
        )->first();

        if (!$exerciseParameter instanceof ExerciseParameter) {
            throw $this->createNotFoundException('Exercise parameter not found');
        }

        $form = $this->createExerciseParameterForm($exerciseParameter);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getExerciseParameterManager()->saveExerciseParameter($exerciseParameter);

        return;
    }

    /**
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param $exercise_id
     * @param $id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($exercise_id, $id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($exercise_id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        if (!$this->getUser() or $this->getUser() !== $exercise->getUser()) {
            throw new AccessDeniedException();
        }

        $exerciseParameter = $exercise->getExerciseParameters()->filter(
            function ($exerciseParameter) use ($id) {
                return $exerciseParameter->getId() == $id;
            }
        )->first();

        if (!$exerciseParameter instanceof ExerciseParameter) {
            throw $this->createNotFoundException('Exercise parameter not found');
        }

        $this->getExerciseParameterManager()->deleteExerciseParameter($exerciseParameter);

        return;
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseManager
     */
    private function getExerciseManager()
    {
        return $this->get('ft_exercise.manager.exercise');
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseParameterManager
     */
    private function getExerciseParameterManager()
    {
        return $this->get('ft_exercise.manager.exercise_parameter');
    }

    /**
     * @param  ExerciseParameter            $exerciseParameter
     * @return \Symfony\Component\Form\Form
     */
    private function createExerciseParameterForm(ExerciseParameter $exerciseParameter)
    {
        $form = $this->createForm(new ExerciseParameterType(), $exerciseParameter, [
            'csrf_protection' => false,
        ]);

        return $form;
    }
}
