<?php

namespace FT\WorkoutBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class WorkoutSetType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sequence')
            ->add('workout', 'entity', [
                'class'         => 'FTWorkoutBundle:Workout',
                'property'      => 'title',
                'query_builder' => function (EntityRepository $er) {
                    // TODO (Stmol) Pass user for select only user owner Workouts
                    return $er->createQueryBuilder('w')
                        ->where('w.isRemoved = :isRemoved')
                        ->setParameter('isRemoved', false);
                },
            ])
            ->add('exercise', 'entity', [
                'class' => 'FTExerciseBundle:Exercise',
                'property' => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.isRemoved = :isRemoved')
                        ->setParameter('isRemoved', false);
                },
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FT\WorkoutBundle\Entity\WorkoutSet',
            'intention'  => 'workout_set_type',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'workout_set';
    }
}
