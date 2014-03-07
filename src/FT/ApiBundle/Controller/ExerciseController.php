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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExerciseController
 * @package FT\ApiBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseController extends AbstractApiController
{
    /**
     * Get all exercises
     *
     * @param  Request  $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $limit = $request->query->get('limit', 50);
        $offset = $request->query->get('offset', 0);

        $exerciseManager = $this->getEntityManager();
        $exercises = $exerciseManager->getAllLimited($limit, $offset);

        $serializer = $this->getSerializer();
        $content = $serializer->serialize($exercises, $request->getRequestFormat());

        return new Response($content);
    }

    /**
     * Create new exercise
     *
     * @param  Request  $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $exercise = $this->getEntityManager()->create();

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $this->getEntityManager()->save($exercise);
            $content = $this->getSerializer()->serialize($exercise, $request->getRequestFormat());

            return new Response($content, 201);
        }

        $errors = $this->getFormErrors($form);
        $content = $this->getSerializer()->serialize($errors, $request->getRequestFormat());

        return new Response($content, 400);
    }

    /**
     * Read exercise
     *
     * @param  Request                                                       $request
     * @param $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function readAction(Request $request, $id)
    {
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $content = $this->getSerializer()->serialize($exercise, $request->getRequestFormat());

        return new Response($content);
    }

    /**
     * Update exercise
     *
     * @param  Request                                                       $request
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $form = $this->createExerciseForm($exercise);
        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $this->getEntityManager()->save($exercise);

            return new Response('', 204);
        }

        $errors = $this->getFormErrors($form);
        $content = $this->getSerializer()->serialize($errors, $request->getRequestFormat());

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
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $this->getEntityManager()->delete($exercise);

        return new Response('', 204);
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseManager
     */
    protected function getEntityManager()
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
