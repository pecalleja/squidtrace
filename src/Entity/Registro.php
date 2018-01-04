<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Registro
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\RegistroRepository")
 */
class Registro
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="lineas", type="integer")
     */
    private $lineas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="decimal", precision=13, scale=3)
     */
    private $timestamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;


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
     * Set lineas
     *
     * @param integer $lineas
     * @return Registro
     */
    public function setLineas($lineas)
    {
        $this->lineas = $lineas;

        return $this;
    }

    /**
     * Get lineas
     *
     * @return integer
     */
    public function getLineas()
    {
        return $this->lineas;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Registro
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Registro
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }
}
