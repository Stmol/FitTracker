<?php

namespace FT\FrontBundle\Controller;

use FT\WorkoutBundle\Entity\Workout;
use FT\WorkoutBundle\Form\WorkoutType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Workout controller.
 *
 */
class WorkoutController extends Controller
{

    /**
     * Lists all Workout entities.
     *
     */
    public function indexAction()
    {
        $workouts = $this->getEntityManager()
            ->getAllLimited(50, 0);

        return $this->render('FTFrontBundle:Workout:index.html.twig', [
            'entities' => $workouts,
        ]);
    }

    /**
     * Finds and displays a Workout entity.
     *
     */
    public function showAction($id)
    {
        $workout = $this->getEntityManager()->getOneById($id);

        if (!$workout or false === $workout->getIsEnabled()) {
            throw $this->createNotFoundException();
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FTFrontBundle:Workout:show.html.twig', [
            'entity'      => $workout,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to create a new Workout entity.
     *
     */
    public function newAction()
    {
        $workout = $this->getEntityManager()->create();
        $form = $this->createCreateForm($workout);

        return $this->render('FTFrontBundle:Workout:new.html.twig', [
            'entity' => $workout,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Workout entity.
     *
     */
    public function editAction($id)
    {
        $workout = $this->getEntityManager()->getOneById($id);

        if (!$workout or false === $workout->getIsEnabled()) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createEditForm($workout);

        return $this->render('FTFrontBundle:Workout:edit.html.twig', [
            'entity'    => $workout,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Creates a new Workout entity.
     *
     */
    public function createAction(Request $request)
    {
        $workout = $this->getEntityManager()->create();

        $form = $this->createCreateForm($workout);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getEntityManager()->save($workout);

            return $this->redirect($this->generateUrl('workouts_show', ['id' => $workout->getId()]));
        }

        return $this->render('FTFrontBundle:Workout:new.html.twig', [
            'entity' => $workout,
            'form'   => $form->createView(),
        ]);
    }

    /**
     * Edits an existing Workout entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $workout = $this->getEntityManager()->getOneById($id);

        if (!$workout or false === $workout->getIsEnabled()) {
            throw $this->createNotFoundException();
        }

        $editForm = $this->createEditForm($workout);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getEntityManager()->save($workout);

            return $this->redirect($this->generateUrl('workouts_show', ['id' => $id]));
        }

        return $this->render('FTFrontBundle:Workout:edit.html.twig', [
            'entity'    => $workout,
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a Workout entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $workout = $this->getEntityManager()->getOneById($id);

            if (!$workout or false === $workout->getIsEnabled()) {
                throw $this->createNotFoundException();
            }

            $this->getEntityManager()->delete($workout);
        }

        return $this->redirect($this->generateUrl('workouts'));
    }

    /**
     * Creates a form to create a Workout entity.
     *
     * @param Workout $workout The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Workout $workout)
    {
        $form = $this->createForm(new WorkoutType(), $workout, [
            'action' => $this->generateUrl('workouts_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a form to edit a Workout entity.
     *
     * @param Workout $workout The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Workout $workout)
    {
        $form = $this->createForm(new WorkoutType(), $workout, [
            'action' => $this->generateUrl('workouts_show', ['id' => $workout->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Creates a form to delete a Workout entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('workouts_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }

    /**
     * @return \FT\WorkoutBundle\Manager\WorkoutManager
     */
    private function getEntityManager()
    {
        return $this->get('ft_workout.manager.workout');
    }
}
