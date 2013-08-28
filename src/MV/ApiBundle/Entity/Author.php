<?php
namespace MV\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * @ORM\Table(name="author")
 * @ORM\Entity()
 */
class Author
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
     * @ORM\Column(type="string", length=255)
     */
    protected $middlename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "3")
     */
    protected $lastname;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="authors")
     */
    protected $playedInMovies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playedInMovies = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Author
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
     * @return Author
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
     * @return Author
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
     * Add playedInMovies
     *
     * @param \MV\ApiBundle\Entity\Movie $playedInMovies
     * @return Author
     */
    public function addPlayedInMovie(\MV\ApiBundle\Entity\Movie $playedInMovies)
    {
        $this->playedInMovies[] = $playedInMovies;

        return $this;
    }

    /**
     * Remove playedInMovies
     *
     * @param \MV\ApiBundle\Entity\Movie $playedInMovies
     */
    public function removePlayedInMovie(\MV\ApiBundle\Entity\Movie $playedInMovies)
    {
        $this->playedInMovies->removeElement($playedInMovies);
    }

    /**
     * Get playedInMovies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlayedInMovies()
    {
        return $this->playedInMovies;
    }
}