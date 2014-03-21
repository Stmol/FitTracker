<?php

namespace FT\WorkoutBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FT\ExerciseBundle\Entity\ExerciseSet;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Workout
 *
 * @ORM\Table(name="workouts")
 * @ORM\Entity(repositoryClass="FT\WorkoutBundle\Entity\Repository\WorkoutRepository")
 */
class Workout
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="brief", type="text", nullable=true)
     */
    private $brief;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FT\ExerciseBundle\Entity\ExerciseSet", mappedBy="workout", cascade={"persist"})
     * @Serializer\SerializedName("sets");
     */
    private $exerciseSets;

    public function __construct()
    {
        $this->exerciseSets = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->isEnabled = true;
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
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * Get brief
     *
     * @return string
     */
    public function getBrief()
    {
        return $this->brief;
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
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set isEnabled
     *
     * @param  boolean $isEnabled
     * @return Workout
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Add exerciseSet
     *
     * @param  ExerciseSet $exerciseSet
     * @return Workout
     */
    public function addExerciseSet(ExerciseSet $exerciseSet)
    {
        $this->exerciseSets->add($exerciseSet);
        $exerciseSet->setWorkout($this);

        return $this;
    }

    /**
     * Remove exerciseSet
     *
     * @param \FT\ExerciseBundle\Entity\ExerciseSet $exerciseSet
     * @param ExerciseSet                           $exerciseSet
     */
    public function removeExerciseSet(ExerciseSet $exerciseSet)
    {
        $this->exerciseSets->removeElement($exerciseSet);
    }

    /**
     * Get exerciseSets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExerciseSets()
    {
        return $this->exerciseSets;
    }
}
