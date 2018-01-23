<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarkerRepository")
 */
class Marker
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $color;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @var Location
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     * @var User
     */
    private $user;

  /**
   * @param Location $location [description]
   */
  public function __construct(Location $location) {
    $this->location = $location;
  }




/**
 * Get the valueof Id
 *
 * @return int
 */
public function getId()
{
    return $this->id;
}

/**
 * Set the value of Id
 *
 * @param int $id
 *
 * @return self
 */
public function setId($id)
{
    $this->id = $id;

    return $this;
}

/**
 * Get the valueof Type
 *
 * @return string
 */
public function getType()
{
    return $this->type;
}

/**
 * Set the value of Type
 *
 * @param string $type
 *
 * @return self
 */
public function setType($type)
{
    $this->type = $type;

    return $this;
}

/**
 * Get the valueof Color
 *
 * @return string
 */
public function getColor()
{
    return $this->color;
}

/**
 * Set the value of Color
 *
 * @param string $color
 *
 * @return self
 */
public function setColor($color)
{
    $this->color = $color;

    return $this;
}

/**
 * Get the valueof Message
 *
 * @return string
 */
public function getMessage()
{
    return $this->message;
}

/**
 * Set the value of Message
 *
 * @param string $message
 *
 * @return self
 */
public function setMessage($message)
{
    $this->message = $message;

    return $this;
}

/**
 * Get the valueof Location
 *
 * @return Location
 */
public function getLocation()
{
    return $this->location;
}

/**
 * Set the value of Location
 *
 * @param Location $location
 *
 * @return self
 */
public function setLocation(Location $location)
{
    $this->location = $location;

    return $this;
}

/**
 * Get the valueof User
 *
 * @return User
 */
public function getUser()
{
    return $this->user;
}

/**
 * Set the value of User
 *
 * @param User $user
 *
 * @return self
 */
public function setUser(User $user)
{
    $this->user = $user;

    return $this;
}

}
