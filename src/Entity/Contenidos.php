<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contenidos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ContenidosRepository")
 */
class Contenidos
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
     * @ORM\Column(name="mime", type="string", length=255, unique=true)
     */
    protected $mime;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    protected $descripcion;

    public function __toString()
    {
        return $this->getMime();
    }

    public function __construct($mime)
    {
        $this->mime = $mime;
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
     * Set mime
     *
     * @param string $mime
     * @return Contenidos
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Contenidos
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