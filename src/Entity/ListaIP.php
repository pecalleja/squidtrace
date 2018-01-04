<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ListaIP
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\ListaIPRepository")
 */
class ListaIP
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
     * @ORM\ManyToMany(targetEntity="App\Entity\AccessLog", mappedBy="ips")
     **/
    protected $logs;


    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", unique=true)
     */
    protected $ip;

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
     * Set ip
     *
     * @param string $ip
     * @return ListaIP
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return ListaIP
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

    public function __construct($ip)
    {
        $this->logs = new ArrayCollection();
        $this->ip = $ip;
    }

    /**
     * Add logs
     *
     * @param \App\Entity\AccessLog $logs
     * @return ListaIP
     */
    public function addLog(AccessLog $logs)
    {
        $this->logs[] = $logs;

        return $this;
    }

    /**
     * Remove logs
     *
     * @param \App\Entity\AccessLog $logs
     */
    public function removeLog(AccessLog $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }
}