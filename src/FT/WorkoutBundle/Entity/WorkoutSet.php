<?php

namespace FT\WorkoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FT\WorkoutBundle\Entity\Workout;
use FT\ExerciseBundle\Entity\Exercise;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * WorkoutSet
 *
 * @ORM\Table(name="workout_sets")
 * @ORM\Entity
 */
class WorkoutSet
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
     * @ORM\Column(name="sequence", type="smallint")
     */
    private $sequence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="removed_at", type="datetimetz", nullable=true)
     */
    private $removedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_removed", type="boolean")
     */
    private $isRemoved;

    /**
     * @var \FT\WorkoutBundle\Entity\Workout
     *
     * @ORM\ManyToOne(targetEntity="Workout", inversedBy="workoutSets")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank()
     */
    private $workout;

    /**
     * @var \FT\ExerciseBundle\Entity\Exercise
     *
     * @ORM\ManyToOne(targetEntity="FT\ExerciseBundle\Entity\Exercise")
     *
     * @Assert\NotBlank(groups={"api"})
     */
    private $exercise;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FT\WorkoutBundle\Entity\WorkoutSetParameter", mappedBy="workoutSet")
     * @Serializer\SerializedName("parameters")
     */
    private $workoutSetParameters;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isRemoved = false;
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
     * Set sequence
     *
     * @param integer $sequence
     * @return WorkoutSet
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return WorkoutSet
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set removedAt
     *
     * @param \DateTime $removedAt
     * @return WorkoutSet
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
    }

    /**
     * Get removedAt
     *
     * @return \DateTime 
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Set isRemoved
     *
     * @param boolean $isRemoved
     * @return WorkoutSet
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    /**
     * Get isRemoved
     *
     * @return boolean 
     */
    public function getIsRemoved()
    {
        return $this->isRemoved;
    }

    /**
     * Set workout
     *
     * @param Workout $workout
     * @return WorkoutSet
     */
    public function setWorkout(Workout $workout = null)
    {
        $this->workout = $workout;

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
     * Set exercise
     *
     * @param Exercise $exercise
     * @return WorkoutSet
     */
    public function setExercise(Exercise $exercise = null)
    {
        $this->exercise = $exercise;

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
     * Add workoutSetParameter
     *
     * @param WorkoutSetParameter $workoutSetParameter
     * @return WorkoutSet
     */
    public function addWorkoutSetParameter(WorkoutSetParameter $workoutSetParameter)
    {
        $this->workoutSetParameters[] = $workoutSetParameter;

        return $this;
    }

    /**
     * Remove workoutSetParameter
     *
     * @param WorkoutSetParameter $workoutSetParameter
     */
    public function removeWorkoutSetParameter(WorkoutSetParameter $workoutSetParameter)
    {
        $this->workoutSetParameters->removeElement($workoutSetParameter);
    }

    /**
     * Get workoutSetParameters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkoutSetParameters()
    {
        return $this->workoutSetParameters;
    }
}
