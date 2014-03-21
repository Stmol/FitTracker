<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseParameterValue
 *
 * @ORM\Table(name="exercise_params_values")
 * @ORM\Entity(repositoryClass="FT\ExerciseBundle\Entity\Repository\ExerciseParameterValueRepository")
 */
class ExerciseParameterValue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     */
    private $value;
    /**
     * @var ExerciseParameter
     *
     * @ORM\ManyToOne(targetEntity="ExerciseParameter")
     * @ORM\JoinColumn(name="exercise_param_id", referencedColumnName="id")
     */
    private $exerciseParameter;
    /**
     * @var ExerciseSet
     *
     * @ORM\ManyToOne(targetEntity="ExerciseSet", inversedBy="exerciseParameterValues")
     * @ORM\JoinColumn(name="exercise_set_id", referencedColumnName="id")
     */
    private $exerciseSet;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param  float                  $value
     * @return ExerciseParameterValue
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get exerciseParameter
     *
     * @return ExerciseParameter
     */
    public function getExerciseParameter()
    {
        return $this->exerciseParameter;
    }

    /**
     * Set exerciseParameter
     *
     * @param  ExerciseParameter      $exerciseParameter
     * @return ExerciseParameterValue
     */
    public function setExerciseParameter(ExerciseParameter $exerciseParameter = null)
    {
        $this->exerciseParameter = $exerciseParameter;

        return $this;
    }

    /**
     * Get exerciseSet
     *
     * @return ExerciseSet
     */
    public function getExerciseSet()
    {
        return $this->exerciseSet;
    }

    /**
     * Set exerciseSet
     *
     * @param  ExerciseSet            $exerciseSet
     * @return ExerciseParameterValue
     */
    public function setExerciseSet(ExerciseSet $exerciseSet = null)
    {
        $this->exerciseSet = $exerciseSet;

        return $this;
    }
}
