<?php

namespace MV\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity()
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $middlename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="watchedBy")
     */
    protected $watchedMovies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->watchedMovies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set middlename
     *
     * @param string $middlename
     * @return User
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;

        return $this;
    }

    /**
     * Get middlename
     *
     * @return string
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Has watchedMovie
     */
    public function hasWachtedMovie(\MV\ApiBundle\Entity\Movie $watchedMovie)
    {
        return $this->watchedMovies->contains($watchedMovie);
    }

    /**
     * Add watchedMovie
     *
     * @param \MV\ApiBundle\Entity\Movie $watchedMovie
     * @return User
     */
    public function addWatchedMovie(\MV\ApiBundle\Entity\Movie $watchedMovie)
    {
        $this->watchedMovies[] = $watchedMovie;

        return $this;
    }

    /**
     * Remove watchedMovie
     *
     * @param \MV\ApiBundle\Entity\Movie $watchedMovie
     */
    public function removeWatchedMovie(\MV\ApiBundle\Entity\Movie $watchedMovie)
    {
        $this->watchedMovies->removeElement($watchedMovie);
    }

    /**
     * Get watchedMovies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWatchedMovies()
    {
        return $this->watchedMovies;
    }
}