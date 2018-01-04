<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redsites
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\RedsitesRepository")
 */
class Redsites
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="patron", type="string")
     */
    protected $patron;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    protected $descripcion;


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
     * Set patron
     *
     * @param string $patron
     * @return Redsites
     */
    public function setPatron($patron)
    {
        $this->patron = $patron;

        return $this;
    }

    /**
     * Get patron
     *
     * @return string
     */
    public function getPatron()
    {
        return $this->patron;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Redsites
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
}