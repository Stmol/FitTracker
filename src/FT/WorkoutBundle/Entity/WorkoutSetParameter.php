<?php

namespace FT\WorkoutBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FT\ExerciseBundle\Entity\ExerciseParameter;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WorkoutSetParameter
 *
 * @ORM\Table(name="wokrout_set_parameters")
 * @ORM\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class WorkoutSetParameter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     *
     * @Serializer\Expose
     *
     * @Assert\NotBlank(groups={"api"})
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="removed_at", type="datetime", nullable=true)
     *
     * @Serializer\Expose
     */
    private $removedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_removed", type="boolean")
     *
     * @Serializer\Expose
     */
    private $isRemoved;

    /**
     * @var ExerciseParameter
     *
     * @ORM\ManyToOne(targetEntity="FT\ExerciseBundle\Entity\ExerciseParameter")
     * @ORM\JoinColumn(name="exercise_parameter_id", nullable=false)
     *
     * @Serializer\Expose
     *
     * @Assert\NotBlank(groups={"api"})
     */
    private $exerciseParameter;

    /**
     * @var WorkoutSet
     *
     * @ORM\ManyToOne(targetEntity="FT\WorkoutBundle\Entity\WorkoutSet", inversedBy="workoutSetParameters")
     * @ORM\JoinColumn(name="workout_set_id", nullable=false)
     *
     * @Serializer\Expose
     * @Serializer\SerializedName("set")
     *
     * @Assert\NotBlank()
     */
    private $workoutSet;

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
     * Set value
     *
     * @param string $value
     * @return WorkoutSetParameter
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return WorkoutSetParameter
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
     * @return WorkoutSetParameter
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
     * @return WorkoutSetParameter
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
     * Set exerciseParameter
     *
     * @param ExerciseParameter $exerciseParameter
     * @return WorkoutSetParameter
     */
    public function setExerciseParameter(ExerciseParameter $exerciseParameter = null)
    {
        $this->exerciseParameter = $exerciseParameter;

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
     * Set workoutSet
     *
     * @param WorkoutSet $workoutSet
     * @return WorkoutSetParameter
     */
    public function setWorkoutSet(WorkoutSet $workoutSet = null)
    {
        $this->workoutSet = $workoutSet;

        return $this;
    }

    /**
     * Get workoutSet
     *
     * @return WorkoutSet
     */
    public function getWorkoutSet()
    {
        return $this->workoutSet;
    }
}
