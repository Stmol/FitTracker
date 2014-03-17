<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercise
 *
 * @ORM\Table(name="exercises")
 * @ORM\Entity(repositoryClass="FT\ExerciseBundle\Entity\Repository\ExerciseRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Exercise
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
     * @Serializer\Expose
     *
     * @Assert\NotBlank()
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
     * @ORM\Column(name="is_enabled", type="boolean")
     *
     * @Serializer\Expose
     */
    private $isEnabled;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ExerciseParameter", mappedBy="exercise", cascade={"persist", "remove"})
     *
     * @Serializer\Expose
     * @Serializer\SerializedName("parameters");
     */
    private $exerciseParameters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exerciseParameters = new ArrayCollection();
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
     * @param  string   $title
     * @return Exercise
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
     * @param string $brief
     * @return $this
     */
    public function setBrief($brief)
    {
        $this->brief = $brief;

        return $this;
    }

    /**
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
     * @return Exercise
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
     * @param  boolean  $isEnabled
     * @return Exercise
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
     * Add Exercise Parameter
     *
     * @param ExerciseParameter $exerciseParameter
     * @return Exercise
     */
    public function addExerciseParameter(ExerciseParameter $exerciseParameter)
    {
        $this->exerciseParameters[] = $exerciseParameter;
        $exerciseParameter->setExercise($this);

        return $this;
    }

    /**
     * Remove Exercise Parameter
     *
     * @param ExerciseParameter $exerciseParameter
     */
    public function removeExerciseParameter(ExerciseParameter $exerciseParameter)
    {
        $this->exerciseParameters->removeElement($exerciseParameter);
    }

    /**
     * Get exerciseParameters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExerciseParameters()
    {
        return $this->exerciseParameters;
    }
}
