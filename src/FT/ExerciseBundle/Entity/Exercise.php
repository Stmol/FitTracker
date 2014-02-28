<?php

namespace FT\ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Exercise
 *
 * @ORM\Table(name="exercises")
 * @ORM\Entity(repositoryClass="FT\ExerciseBundle\Entity\ExerciseRepository")
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
     */
    private $title;

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

    public function __construct()
    {
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
     * @param string $title
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
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
     * @param boolean $isEnabled
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
}
