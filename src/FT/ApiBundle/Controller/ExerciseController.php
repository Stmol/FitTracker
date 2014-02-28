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
use FT\ExerciseBundle\Form\ExerciseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $limit = $request->query->get('limit', 50);
        $offset = $request->query->get('offset', 0);

        $exerciseManager = $this->getExerciseManager();
        $exercises = $exerciseManager->getExercises($limit, $offset);

        $serializer = $this->getSerializer();
        $content = $serializer->serialize($exercises, $request->getRequestFormat());

        return new Response($content);
    }

    /**
     * Create new exercise
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $exerciseManager = $this->getExerciseManager();
        $exercise = $exerciseManager->createExercise();

        $serializer = $this->getSerializer();

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $exerciseManager->saveExercise($exercise);
            $content = $serializer->serialize($exercise, $request->getRequestFormat());

            return new Response($content, 201);
        }

        $errors = $this->getFormErrors($form);
        $content = $serializer->serialize($errors, $request->getRequestFormat());

        return new Response($content, 400);
    }

    /**
     * Read exercise
     *
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function readAction(Request $request, $id)
    {
        $exerciseManager = $this->getExerciseManager();
        $exercise = $exerciseManager->getExercise($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $serializer = $this->getSerializer();
        $content = $serializer->serialize($exercise, $request->getRequestFormat());

        return new Response($content);
    }

    /**
     * Update exercise
     *
     * @param Request $request
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $exerciseManager = $this->getExerciseManager();
        $exercise = $exerciseManager->getExercise($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $serializer = $this->getSerializer();

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $exerciseManager->saveExercise($exercise);

            return new Response('', 204);
        }

        $errors = $this->getFormErrors($form);
        $content = $serializer->serialize($errors, $request->getRequestFormat());

        return new Response($content, 400);
    }

    /**
     * Delete exercise
     *
     * @param $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $exerciseManager = $this->getExerciseManager();
        $exercise = $exerciseManager->getExercise($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $exercise->setIsEnabled(false);
        $exerciseManager->saveExercise($exercise);

        return new Response('', 204);
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseManager
     */
    private function getExerciseManager()
    {
        return $this->get('ft_exercise.manager.exercise');
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    private function getSerializer()
    {
        return $this->get('jms_serializer');
    }

    /**
     * Parse form errors
     *
     * @param FormInterface $form
     * @return array
     */
    private function getFormErrors(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = $this->getFormErrors($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }

    /**
     * Create exercise type form
     *
     * @param Exercise $exercise
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
