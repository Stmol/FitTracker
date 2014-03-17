<?php

namespace FT\ExerciseBundle\Form\Type;

use FT\ExerciseBundle\Entity\ExerciseParameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExerciseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'exercise.title',
            ])
            ->add('brief', 'textarea', [
                'label' => 'exercise.brief',
            ])
            ->add('exerciseParameters', 'collection', [
                'type'         => new ExerciseParameterType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => 'exercise.exercise_parameters',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FT\ExerciseBundle\Entity\Exercise',
            'intention'  => 'exercise_type',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'exercise';
    }
}
