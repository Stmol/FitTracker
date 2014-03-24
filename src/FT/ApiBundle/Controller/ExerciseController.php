<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 23.02.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use FT\ExerciseBundle\Entity\Exercise;
use FT\ExerciseBundle\Form\Type\ExerciseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ExerciseController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseController extends Controller
{
    /**
     * Get all exercises
     *
     * @Rest\View(statusCode=200)
     *
     * @param  Request  $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit', 50);
        $offset = $request->query->get('offset', 0);
        $isRemoved = $request->query->has('is_removed');

        $exerciseManager = $this->getExerciseManager();
        $exercises = $exerciseManager->findExercisesLimited($isRemoved, $limit, $offset);

        return $exercises;
    }

    /**
     * Create new exercise
     *
     * @Rest\View(statusCode=201)
     * @Security("has_role('ROLE_API')")
     *
     * @param  Request $request
     * @return \FT\ExerciseBundle\Entity\Exercise|\Symfony\Component\Form\Form
     */
    public function createAction(Request $request)
    {
        $exercise = $this->getExerciseManager()->createExercise($this->getUser());

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getExerciseManager()->saveExercise($exercise);

        return $exercise;
    }

    /**
     * Read exercise
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \FT\ExerciseBundle\Entity\Exercise
     */
    public function readAction(Request $request, $id)
    {
        $exercise = $this->getExerciseManager()
            ->findExerciseById($id, $request->query->has('is_removed'));

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        return $exercise;
    }

    /**
     * Update exercise
     *
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param  Request $request
     * @param $id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        if (!$this->getUser() or $this->getUser() !== $exercise->getUser()) {
            throw new AccessDeniedException();
        }

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if (!$form->isValid()) {
            return $form;
        }

        $this->getExerciseManager()->saveExercise($exercise);

        return;
    }

    /**
     * Delete exercise
     *
     * @Rest\View(statusCode=204)
     * @Security("has_role('ROLE_API')")
     *
     * @param $id
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function deleteAction($id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        if (!$this->getUser() or $this->getUser() !== $exercise->getUser()) {
            throw new AccessDeniedException();
        }

        $this->getExerciseManager()->deleteExercise($exercise);

        return;
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseManager
     */
    protected function getExerciseManager()
    {
        return $this->get('ft_exercise.manager.exercise');
    }

    /**
     * Create exercise type form
     *
     * @param  Exercise                     $exercise
     * @return \Symfony\Component\Form\Form
     */
    private function createExerciseForm(Exercise $exercise)
    {
        $form = $this->createForm(new ExerciseType(), $exercise, array(
            'csrf_protection' => false,
        ));

        return $form;
    }
}
