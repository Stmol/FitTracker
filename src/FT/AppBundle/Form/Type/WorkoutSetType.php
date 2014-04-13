<?php

namespace FT\AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
                'class'         => 'FTAppBundle:Workout',
                'property'      => 'title',
                'query_builder' => function (EntityRepository $er) {
                    // TODO (Stmol) Pass user for select only user owner Workouts
                    return $er->createQueryBuilder('w')
                        ->where('w.isRemoved = :isRemoved')
                        ->setParameter('isRemoved', false);
                },
            ])
            ->add('exercise', 'entity', [
                'class' => 'FTAppBundle:Exercise',
                'property' => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.isRemoved = :isRemoved')
                        ->setParameter('isRemoved', false);
                },
            ])
            ->add('workoutSetParameters', 'collection', [
                'type'         => new WorkoutSetParameterType(),
                'allow_delete' => true,
                'allow_add'    => true,
                'by_reference' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FT\AppBundle\Entity\WorkoutSet',
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
