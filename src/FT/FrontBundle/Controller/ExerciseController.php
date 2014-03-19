<?php

namespace FT\FrontBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use FT\ExerciseBundle\Entity\Exercise;
use FT\ExerciseBundle\Form\Type\ExerciseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Exercise controller.
 *
 */
class ExerciseController extends Controller
{
    /**
     * Lists all Exercise entities.
     *
     */
    public function indexAction()
    {
        $exercises = $this->getEntityManager()
            ->getAllLimited(50, 0);

        return $this->render('FTFrontBundle:Exercise:index.html.twig', [
            'entities' => $exercises,
        ]);
    }

    /**
     * Finds and displays a Exercise entity.
     *
     */
    public function showAction($id)
    {
        /** @var Exercise $exercise */
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise or false === $exercise->getIsEnabled()) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FTFrontBundle:Exercise:show.html.twig', [
            'exercise'    => $exercise,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to create a new Exercise entity.
     *
     */
    public function newAction()
    {
        $exercise = $this->getEntityManager()->create();
        $form = $this->createCreateForm($exercise);

        return $this->render('FTFrontBundle:Exercise:new.html.twig', [
            'entity' => $exercise,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Exercise entity.
     *
     */
    public function editAction($id)
    {
        /** @var Exercise $exercise */
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise or false === $exercise->getIsEnabled()) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $editForm = $this->createEditForm($exercise);

        return $this->render('FTFrontBundle:Exercise:edit.html.twig', [
            'entity' => $exercise,
            'form'   => $editForm->createView(),
        ]);
    }

    /**
     * Creates a new Exercise entity.
     *
     */
    public function createAction(Request $request)
    {
        $exercise = $this->getEntityManager()->create();

        $form = $this->createCreateForm($exercise);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityManager()->save($exercise);

            return $this->redirect($this->generateUrl('exercises_show', ['id' => $exercise->getId()]));
        }

        return $this->render('FTFrontBundle:Exercise:new.html.twig', [
            'entity' => $exercise,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Edits an existing Exercise entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        /** @var Exercise $exercise */
        $exercise = $this->getEntityManager()->getOneById($id);

        if (!$exercise or false === $exercise->getIsEnabled()) {
            throw $this->createNotFoundException('Exercise not found');
        }

        $editForm = $this->createEditForm($exercise);
        $existsExerciseParams = new ArrayCollection();

        foreach ($exercise->getExerciseParameters() as $parameter) {
            $existsExerciseParams->add($parameter);
        }

        if ($editForm->handleRequest($request)->isValid()) {
            foreach ($existsExerciseParams as $parameter) {
                if (false === $exercise->getExerciseParameters()->contains($parameter)) {
                    $this->get('ft_exercise.manager.exercise_parameter')->delete($parameter);
                }
            }

            $this->getEntityManager()->save($exercise);

            return $this->redirect($this->generateUrl('exercises_show', ['id' => $id]));
        }

        return $this->render('FTFrontBundle:Exercise:edit.html.twig', [
            'entity'    => $exercise,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Exercise entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Exercise $exercise */
            $exercise = $this->getEntityManager()->getOneById($id);

            if (!$exercise or false === $exercise->getIsEnabled()) {
                throw $this->createNotFoundException('Exercise not found');
            }

            $this->getEntityManager()->delete($exercise);
        }

        return $this->redirect($this->generateUrl('exercises'));
    }

    /**
     * Creates a form to create a Exercise entity.
     *
     * @param Exercise $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Exercise $entity)
    {
        $form = $this->createForm(new ExerciseType(), $entity, [
            'action'          => $this->generateUrl('exercises_create'),
            'method'          => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Сreate']);

        return $form;
    }

    /**
     * Creates a form to edit a Exercise entity.
     *
     * @param Exercise $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Exercise $entity)
    {
        $form = $this->createForm(new ExerciseType(), $entity, [
            'action' => $this->generateUrl('exercises_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to delete a Exercise entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('exercises_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @return \FT\ExerciseBundle\Manager\ExerciseManager
     */
    private function getEntityManager()
    {
        return $this->get('ft_exercise.manager.exercise');
    }
}
