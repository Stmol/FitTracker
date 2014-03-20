<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FT\ExerciseBundle\Entity\Exercise;
use FT\WorkoutBundle\Entity\Workout;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * ExerciseSet
 *
 * @ORM\Table(name="sets")
 * @ORM\Entity(repositoryClass="FT\ExerciseBundle\Entity\Repository\ExerciseSetRepository")
 */
class ExerciseSet
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
     * @var integer
     *
     * @ORM\Column(name="sequence", type="integer")
     */
    private $sequence;
    /**
     * @ORM\ManyToOne(targetEntity="Exercise")
     */
    private $exercise;
    /**
     * @ORM\ManyToOne(targetEntity="FT\WorkoutBundle\Entity\Workout", inversedBy="exerciseSets")
     */
    private $workout;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ExerciseParameterValue", mappedBy="exerciseSet")
     */
    private $exerciseParameterValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exerciseParameterValues = new ArrayCollection();
    }

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
     * Get sequence
     *
     * @return integer
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set sequence
     *
     * @param integer $sequence
     * @return ExerciseSet
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get exercise
     *
     * @return Exercise
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * Set exercise
     *
     * @param Exercise $exercise
     * @return ExerciseSet
     */
    public function setExercise(Exercise $exercise = null)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get workout
     *
     * @return Workout
     */
    public function getWorkout()
    {
        return $this->workout;
    }

    /**
     * Set workout
     *
     * @param Workout $workout
     * @return ExerciseSet
     */
    public function setWorkout(Workout $workout = null)
    {
        $this->workout = $workout;

        return $this;
    }

    /**
     * Add exerciseParameterValue
     *
     * @param ExerciseParameterValue $exerciseParameterValue
     * @return ExerciseSet
     */
    public function addExerciseParameterValue(ExerciseParameterValue $exerciseParameterValue)
    {
        $this->exerciseParameterValues[] = $exerciseParameterValue;

        return $this;
    }

    /**
     * Remove exerciseParameterValue
     *
     * @param ExerciseParameterValue $exerciseParameterValue
     */
    public function removeExerciseParameterValue(ExerciseParameterValue $exerciseParameterValue)
    {
        $this->exerciseParameterValues->removeElement($exerciseParameterValue);
    }

    /**
     * Get exerciseParameterValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExerciseParameterValues()
    {
        return $this->exerciseParameterValues;
    }
}
