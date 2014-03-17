<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExerciseParameterManager
 *
 * @ORM\Table(name="exercise_params")
 * @ORM\Entity(repositoryClass="FT\ExerciseBundle\Entity\Repository\ExerciseParameterRepository")
 */
class ExerciseParameter
{
    const
        KIND_WEIGHT   = 'weight',
        KIND_NUMBER   = 'number',
        KIND_TIME     = 'time',
        KIND_DISTANCE = 'distance'
    ;

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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="kind", type="string", length=255)
     */
    private $kind;

    /**
     * @var Exercise
     *
     * @ORM\ManyToOne(targetEntity="Exercise", inversedBy="exerciseParameters")
     */
    private $exercise;

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
     * @param string $title
     * @return ExerciseParameter
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
     * @param string $kind
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setKind($kind)
    {
        if (!in_array($kind, $this->getKindValues())) {
            throw new \InvalidArgumentException("Invalid kind");
        }

        $this->kind = $kind;

        return $this;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @return array
     */
    public function getKindValues()
    {
        return [
            self::KIND_WEIGHT,
            self::KIND_NUMBER,
            self::KIND_TIME,
            self::KIND_DISTANCE,
        ];
    }

    /**
     * Set exercise
     *
     * @param Exercise $exercise
     * @return ExerciseParameter
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
}
