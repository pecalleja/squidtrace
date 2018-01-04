<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AccessLog
 *
 * @ORM\Table(name="accesslog",uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"usuario_id", "url","fecha","hora"})})
 * @ORM\Entity(repositoryClass="App\Repository\AccessLogRepository")
 */
class AccessLog
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CargaMime", mappedBy="log")
     */
    protected $contenidos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ListaIP", inversedBy="logs")
     * @ORM\JoinTable(name="logs_ips")
     **/
    protected $ips;

    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="access")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;

    /**
     * @var string
     * @ORM\Column(name="url", type="string")
     */
    protected $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="alias", type="boolean")
     */
    protected $alias;

    /**
     * @var \DateTime
     * @ORM\Column(name="fecha", type="date")
     */
    protected $fecha;

    /**
     * @var integer
     * @ORM\Column(name="hora", type="smallint")
     */
    protected $hora;

    /**
     * @var integer
     *
     * @ORM\Column(name="carga", type="integer")
     */
    protected $carga;

    /**
     * @var boolean
     *
     * @ORM\Column(name="free", type="boolean")
     */
    protected $free;

    /**
     * @var integer
     *
     * @ORM\Column(name="visitas", type="integer")
     */
    protected $visitas;

    public function __construct()
    {
        $this->contenidos = new ArrayCollection();
        $this->ips = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     * @return AccessLog
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alias
     *
     * @param boolean $alias
     * @return AccessLog
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return boolean
     */
    public function getAlias()
    {
        return $this->alias;
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
     * Get hora
     *
     * @return integer
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set carga
     *
     * @param integer $carga
     * @return AccessLog
     */
    public function setCarga($carga)
    {
        $this->carga = $carga;

        return $this;
    }

    /**
     * Get carga
     *
     * @return integer
     */
    public function getCarga()
    {
        return $this->carga;
    }

    /**
     * Set free
     *
     * @param boolean $free
     * @return AccessLog
     */
    public function setFree($free)
    {
        $this->free = $free;

        return $this;
    }

    /**
     * Get free
     *
     * @return boolean
     */
    public function getFree()
    {
        return $this->free;
    }


    /**
     * Add contenidos
     *
     * @param \App\Entity\CargaMime $contenidos
     * @return AccessLog
     */
    public function addContenido(CargaMime $contenidos)
    {
        $this->contenidos[] = $contenidos;

        return $this;
    }

    /**
     * Remove contenidos
     *
     * @param \App\Entity\CargaMime $contenidos
     */
    public function removeContenido(CargaMime $contenidos)
    {
        $this->contenidos->removeElement($contenidos);
    }

    /**
     * Get contenidos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContenidos()
    {
        return $this->contenidos;
    }

    /**
     * Add ips
     *
     * @param \App\Entity\ListaIP $ips
     * @return AccessLog
     */
    public function addIp(ListaIP $ips)
    {
        $this->ips[] = $ips;

        return $this;
    }

    /**
     * Remove ips
     *
     * @param \App\Entity\ListaIP $ips
     */
    public function removeIp(ListaIP $ips)
    {
        $this->ips->removeElement($ips);
    }

    /**
     * Get ips
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * Set usuario
     *
     * @param \App\Entity\Usuario $usuario
     * @return AccessLog
     */
    public function setUsuario(Usuario $usuario)
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
     * Set url
     *
     * @param string $url
     * @return AccessLog
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return AccessLog
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Set hora
     *
     * @param integer $hora
     * @return AccessLog
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Set visitas
     *
     * @param integer $visitas
     * @return AccessLog
     */
    public function setVisitas($visitas)
    {
        $this->visitas = $visitas;

        return $this;
    }

    /**
     * Get visitas
     *
     * @return integer
     */
    public function getVisitas()
    {
        return $this->visitas;
    }
}