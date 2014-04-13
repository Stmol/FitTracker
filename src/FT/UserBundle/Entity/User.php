<?php

namespace FT\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as Unique;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use FT\AppBundle\Entity\Exercise;
use FT\AppBundle\Entity\Workout;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="FT\UserBundle\Entity\UserRepository")
 *
 * @Unique(
 *      "username",
 *       message="ft_user.username.already_used",
 *       groups={"registration"}
 * )
 *
 * @Serializer\ExclusionPolicy("all")
 */
class User implements UserInterface
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message="ft_user.username.blank", groups={"registration"})
     * @Assert\Length(
     *      min="2",
     *      max="30",
     *      minMessage="ft_user.username.short",
     *      maxMessage="ft_user.username.long",
     *      groups={"registration"}
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9]+$/i",
     *     htmlPattern = "^[a-zA-Z0-9]+$",
     *     message="ft_user.username.only_english",
     *     groups={"registration"}
     * )
     *
     * @Serializer\Expose
     */
    private $username;
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
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
     * @var string
     *
     * @Assert\NotBlank(message="ft_user.plain_password.blank", groups={"registration"})
     * @Assert\Length(
     *      min="5",
     *      minMessage="ft_user.plain_password.short",
     *      groups={"registration"}
     * )
     */
    private $plainPassword;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="\FT\AppBundle\Entity\Exercise", mappedBy="user")
     */
    private $exercises;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FT\AppBundle\Entity\Workout", mappedBy="user")
     */
    private $workouts;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->createdAt = new \DateTime();
        $this->exercises = new ArrayCollection();
        $this->isRemoved = false;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param  string $plainPassword
     * @return $this
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

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
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param  string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param  string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set salt
     *
     * @param  string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

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
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Add exercises
     *
     * @param  Exercise $exercise
     * @return User
     */
    public function addExercise(Exercise $exercise)
    {
        $this->exercises[] = $exercise;

        return $this;
    }

    /**
     * Remove exercises
     *
     * @param \FT\ExerciseBundle\Entity\Exercise $exercise
     */
    public function removeExercise(Exercise $exercise)
    {
        $this->exercises->removeElement($exercise);
    }

    /**
     * Get exercises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    /**
     * Add workouts
     *
     * @param Workout $workout
     * @return User
     */
    public function addWorkout(Workout $workout)
    {
        $this->workouts[] = $workout;

        return $this;
    }

    /**
     * Remove workouts
     *
     * @param Workout $workouts
     */
    public function removeWorkout(Workout $workouts)
    {
        $this->workouts->removeElement($workouts);
    }

    /**
     * Get workouts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkouts()
    {
        return $this->workouts;
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
     * Set removedAt
     *
     * @param \DateTime $removedAt
     * @return User
     */
    public function setRemovedAt($removedAt)
    {
        $this->removedAt = $removedAt;

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
     * @param boolean $isRemoved
     * @return User
     */
    public function setIsRemoved($isRemoved)
    {
        $this->isRemoved = $isRemoved;

        return $this;
    }
}
