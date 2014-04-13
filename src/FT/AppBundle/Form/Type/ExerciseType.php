<?php

namespace FT\AppBundle\Form\Type;

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
                'label' => 'entity.exercise.property.title',
            ])
            ->add('brief', 'textarea', [
                'label' => 'entity.exercise.property.brief',
            ])
            ->add('exerciseParameters', 'collection', [
                'type'         => new ExerciseParameterType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label'        => 'entity.exercise.property.exercise_parameters',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FT\AppBundle\Entity\Exercise',
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
