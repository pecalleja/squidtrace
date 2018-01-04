<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bloqueados
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\BloqueadosRepository")
 */
class Bloqueados
{
    /**
     * @var Usuario
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
     * @var string
     *
     * @ORM\Column(name="comentario", type="text", nullable=true)
     */
    protected $comentario;

    public function __construct()
    {
        $this->fecha = new \DateTime();
    }


    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Bloqueados
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Bloqueados
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
     * Set comentario
     *
     * @param string $comentario
     * @return Bloqueados
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }
}