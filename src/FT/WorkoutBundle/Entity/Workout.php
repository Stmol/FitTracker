<?php

namespace FT\WorkoutBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FT\ExerciseBundle\Entity\ExerciseSet;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use FT\UserBundle\Entity\User;

/**
 * Workout
 *
 * @ORM\Table(name="workouts")
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Workout
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
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Serializer\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="text", nullable=true)
     *
     * @Serializer\Expose
     */
    private $brief;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_removed", type="boolean")
     *
     * @Serializer\Expose
     */
    private $isRemoved;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="removed_at", type="datetimetz", nullable=true)
     *
     * @Serializer\Expose
     */
    private $removedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="FT\UserBundle\Entity\User", inversedBy="workouts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="WorkoutSet", mappedBy="workout")
     *
     * @Serializer\Expose
     * @Serializer\SerializedName("sets")
     */
    private $workoutSets;

    public function __construct()
    {
        $this->exerciseSets = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->isRemoved = false;
    }

    /**
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * @param \DateTime $removedAt
     * @return Workout
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;

        return $this;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string  $title
     * @return Workout
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get brief
     *
     * @return string
     */
    public function getBrief()
    {
        return $this->brief;
    }

    /**
     * Set brief
     *
     * @param  string  $brief
     * @return Workout
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Workout
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

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
     * Set isRemoved
     *
     * @param  boolean $isRemoved
     * @return Workout
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Workout
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add workoutSet
     *
     * @param WorkoutSet $workoutSet
     * @return Workout
     */
    public function addWorkoutSet(WorkoutSet $workoutSet)
    {
        $this->workoutSets[] = $workoutSet;

        return $this;
    }

    /**
     * Remove workoutSet
     *
     * @param WorkoutSet $workoutSet
     */
    public function removeWorkoutSet(WorkoutSet $workoutSet)
    {
        $this->workoutSets->removeElement($workoutSet);
    }

    /**
     * Get workoutSets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkoutSets()
    {
        return $this->workoutSets;
    }
}
