<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * Many Images have One Location.
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="images")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $caption;

   /**
    * @ORM\Column(type="string")
    *
    * @var string
    */

    private $src;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="submitter_id", referencedColumnName="id")
     * @var User
     */
    private $user;

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
     * Get the valueof Caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set the value of Caption
     *
     * @param string $caption
     *
     * @return self
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get the valueof Img
     *
     * @return resource
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set the value of Img
     *
     * @param resource $img
     *
     * @return self
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }


    /**
     * Get the valueof Src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set the value of Src
     *
     * @param string $src
     *
     * @return self
     */
    public function setSrc($src)
    {
        $this->src = $src;

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
