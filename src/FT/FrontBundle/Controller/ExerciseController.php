<?php

namespace FT\FrontBundle\Controller;

use FT\AppBundle\Entity\Exercise;
use FT\AppBundle\Form\Type\ExerciseType;
use FT\FrontBundle\Service\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Routing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ExerciseController
 * @package FT\FrontBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 *
 * @Routing\Route("/exercises")
 */
class ExerciseController extends Controller
{
    const EXERCISE_PER_PAGE = 20;

    /**
     * @Routing\Route("/", name="exercise_index")
     * @Routing\Method("GET")
     * @Routing\Template()
     */
    public function indexAction(Request $request)
    {
        $paginator = new Paginator();

        $exercises = $paginator->paginate(
            $this->getExerciseManager()->getExerciseRepository()->getExerciseQueryBuilder(),
            $request->query->get('page', 1),
            self::EXERCISE_PER_PAGE
        );

        return [
            'exercises' => $exercises,
            'paginator' => $paginator,
        ];
    }

    /**
     * @Routing\Route("/{id}", name="exercise_show")
     * @Routing\Method("GET")
     * @Routing\Template()
     *
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return array
     */
    public function showAction($id)
    {
        $exercise = $this->getExerciseManager()->findExerciseById($id);

        if (!$exercise instanceof Exercise) {
            throw $this->createNotFoundException();
        }

        return [
            'exercise' => $exercise,
        ];
    }

    /**
     * @Routing\Route("/new", name="exercise_new")
     * @Routing\Method("GET")
     * @Routing\Template()
     */
    public function newAction()
    {
        $exercise = $this->getExerciseManager()->createExercise($this->getUser());
        $form = $this->createExerciseForm($exercise);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Routing\Route("/", name="exercise_create")
     * @Routing\Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException;
        }

        $exercise = $this->getExerciseManager()->createExercise($user);
        $form = $this->createExerciseForm($exercise);

        if ($form->handleRequest($request)->isValid()) {
            $this->getExerciseManager()->saveExercise($exercise);

            return $this->redirect($this->generateUrl('exercise_index'));
        }

        return $this->render('FTAppBundle:Exercise:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \FT\AppBundle\Service\Manager\ExerciseManager
     */
    private function getExerciseManager()
    {
        return $this->get('ft_app.manager.exercise');
    }

    /**
     * @param Exercise $exercise
     * @return \Symfony\Component\Form\Form
     */
    private function createExerciseForm(Exercise $exercise)
    {
        $form = $this->createForm(new ExerciseType(), $exercise, [
            'method' => 'POST',
            'action' => $this->generateUrl('exercise_create'),
        ]);

        $form
            ->add('add', 'submit', ['label' => 'form.button.add'])
        ;

        return $form;
    }
}
