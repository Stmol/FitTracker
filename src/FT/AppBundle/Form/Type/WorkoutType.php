<?php

namespace FT\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkoutType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'workout.title'
            ])
            ->add('brief', 'textarea', [
                'label' => 'workout.brief'
            ])
            ->add('workoutSets', 'collection', [
                'type'         => new WorkoutSetType(),
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
            'data_class' => 'FT\AppBundle\Entity\Workout',
            'intention'  => 'workout_type',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'workout';
    }
}
