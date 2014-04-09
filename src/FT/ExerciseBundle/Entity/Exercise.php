<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use FT\UserBundle\Entity\User;

/**
 * Exercise
 *
 * @ORM\Table(name="exercises")
 * @ORM\Entity()
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ExerciseParameter", mappedBy="exercise", cascade={"persist", "remove"})
     *
     * @Serializer\Expose
     * @Serializer\SerializedName("parameters")
     */
    private $exerciseParameters;
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\FT\UserBundle\Entity\User", inversedBy="exercises")
     *
     * @Serializer\Expose
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exerciseParameters = new ArrayCollection();
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
     * @param  \DateTime $removedAt
     * @return Exercise
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
     * @param  string   $title
     * @return Exercise
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @param  string $brief
     * @return $this
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
     * @return Exercise
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
     * @param  boolean  $isRemoved
     * @return Exercise
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    /**
     * Add Exercise Parameter
     *
     * @param  ExerciseParameter $exerciseParameter
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

    /**
     * Get user
     *
     * @return \FT\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param  User     $user
     * @return Exercise
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
