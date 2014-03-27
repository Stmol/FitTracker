<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 17.03.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ExerciseBundle\Form\Type;

use FT\ExerciseBundle\Entity\ExerciseParameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ExerciseParameterType
 * @package FT\ExerciseBundle\Form\Type
 * @author Yury Smidovich <dev@stmol.me>
 */
class ExerciseParameterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'exercise_parameter.title'
            ])
            ->add('type', 'choice', [
                'label'       => 'exercise_parameter.kind',
                'empty_value' => false,
                'choices'     => [
                    ExerciseParameter::TYPE_NUMBER   => 'quantity',
                    ExerciseParameter::TYPE_WEIGHT   => 'weight',
                    ExerciseParameter::TYPE_TIME     => 'time',
                    ExerciseParameter::TYPE_DISTANCE => 'distance',
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'FT\ExerciseBundle\Entity\ExerciseParameter',
            'intention'  => 'exercise_parameter_type',
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'exercise_parameter';
    }
}
