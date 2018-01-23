<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=27)
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $address;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     * @var string
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", scale=7, nullable=true)
     * @var string
     */
    private $longitude;

    /**
     * Many Locatinos have Many Images.
     * @ORM\ManyToMany(targetEntity="Image")
     * @ORM\JoinTable(name="images",
     *      joinColumns={@ORM\JoinColumn(name="location_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     * )
     * @var ArrayCollection
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * Initializes array for images
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Get the valueof Id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param string $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the valueof Address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of Address
     *
     * @param string $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the valueof Latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set the value of Latitude
     *
     * @param string $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the valueof Longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set the value of Longitude
     *
     * @param string $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the valueof Images
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images->toArray();
    }

    /**
     * Set the value of Images
     *
     * @param Image $image
     *
     * @return self
     */
    public function addImage(Image $image)
    {
        $this->images->add($image);

        return $this;
    }

    public function getImage(int $key)
    {
        $this->images->get($key);

        return $this;
    }

    public function removeImage(int $key)
    {
        $this->images->remove($key);

        return $this;
    }

/**
 * Set the value of Many Locatinos have Many Images.
 *
 * @param ArrayCollection $images
 *
 * @return self
 */
    public function setImages(ArrayCollection $images)
    {
        $this->images = $images;

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
