<?php

namespace FT\WorkoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkoutType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FT\WorkoutBundle\Entity\Workout'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ft_workoutbundle_workout';
    }
}
