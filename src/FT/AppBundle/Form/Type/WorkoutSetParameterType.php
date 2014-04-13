<?php

namespace FT\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class WorkoutSetParameterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('exerciseParameter', 'entity', [
                'class'         => 'FTAppBundle:ExerciseParameter',
                'property'      => 'title',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ep')
                        ->where('ep.isRemoved = :isRemoved')
                        ->setParameter('isRemoved', false);
                },
            ])
            ->add('workoutSet', 'entity', [
                'class'         => 'FTAppBundle:WorkoutSet',
                'property'      => 'sequence',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ws')
                        ->where('ws.isRemoved = :isRemoved')
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
        $resolver->setDefaults(array(
            'data_class' => 'FT\AppBundle\Entity\WorkoutSetParameter',
            'intention'  => 'workout_set_parameter_type',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'workout_set_parameter';
    }
}
