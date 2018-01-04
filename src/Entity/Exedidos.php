<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exedidos
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ExedidosRepository")
 */
class Exedidos
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\OneToOne(targetEntity="App\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="login")
     */
    protected $usuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    protected $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="sobregiro", type="integer")
     */
    protected $sobregiro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bloqueo", type="boolean")
     */
    protected $bloqueo;

    public function __construct()
    {
        $this->fecha = new \DateTime();
    }


    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Exedidos
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Exedidos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set sobregiro
     *
     * @param integer $sobregiro
     * @return Exedidos
     */
    public function setSobregiro($sobregiro)
    {
        $this->sobregiro = $sobregiro;

        return $this;
    }

    /**
     * Get sobregiro
     *
     * @return integer
     */
    public function getSobregiro()
    {
        return $this->sobregiro;
    }

    /**
     * Set bloqueo
     *
     * @param boolean $bloqueo
     * @return Exedidos
     */
    public function setBloqueo($bloqueo)
    {
        $this->bloqueo = $bloqueo;

        return $this;
    }

    /**
     * Get bloqueo
     *
     * @return boolean
     */
    public function getBloqueo()
    {
        return $this->bloqueo;
    }
}