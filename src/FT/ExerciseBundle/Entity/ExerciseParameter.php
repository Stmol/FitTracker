<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ExerciseParameterManager
 *
 * @ORM\Table(name="exercise_parameters")
 * @ORM\Entity()
 *
 * @Serializer\ExclusionPolicy("all")
 */
class ExerciseParameter
{
    const
        TYPE_WEIGHT   = 'weight',
        TYPE_NUMBER   = 'quantity',
        TYPE_TIME     = 'time',
        TYPE_DISTANCE = 'distance'
    ;

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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @Serializer\Expose
     */
    private $type;

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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     *
     * @Serializer\Expose
     */
    private $createdAt;

    /**
     * @var Exercise
     *
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="exerciseParameters")
     *
     * @Serializer\Expose
     */
    private $exercise;

    public function __construct()
    {
        $this->isRemoved = false;
        $this->createdAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime         $createdAt
     * @return ExerciseParameter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRemoved()
    {
        return $this->isRemoved;
    }

    /**
     * Set isRemoved
     *
     * @param  boolean           $isRemoved
     * @return ExerciseParameter
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRemovedAt()
    {
        return $this->removedAt;
    }

    /**
     * Set removedAt
     *
     * @param  \DateTime         $removedAt
     * @return ExerciseParameter
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
     * @param  string            $title
     * @return ExerciseParameter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Type
     *
     * @param  string                    $type
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setType($type)
    {
        if (!in_array($type, $this->getTypeValues())) {
            throw new \InvalidArgumentException("Invalid kind");
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getTypeValues()
    {
        return [
            self::TYPE_WEIGHT,
            self::TYPE_NUMBER,
            self::TYPE_TIME,
            self::TYPE_DISTANCE,
        ];
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
     * @param  Exercise          $exercise
     * @return ExerciseParameter
     */
    public function setExercise(Exercise $exercise = null)
    {
        $this->exercise = $exercise;

        return $this;
    }
}
