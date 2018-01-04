<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CargaMime
 *
 * @ORM\Table(name="cargamime",uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"log_id", "mime_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\CargaMimeRepository")
 */
class CargaMime
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var AccessLog
     * @ORM\ManyToOne(targetEntity="App\Entity\AccessLog", inversedBy="contenidos")
     * @ORM\JoinColumn(name="log_id", referencedColumnName="id")
     */
    protected $log;

    /**
     * @var Contenidos
     * @ORM\ManyToOne(targetEntity="App\Entity\Contenidos")
     * @ORM\JoinColumn(name="mime_id", referencedColumnName="id")
     */
    protected $mime;

    /**
     * @var integer
     *
     * @ORM\Column(name="carga", type="integer")
     */
    protected $carga;


    /**
     * Set id
     *
     * @param integer $id
     * @return CargaMime
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
     * Set carga
     *
     * @param integer $carga
     * @return CargaMime
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
     * Set log
     *
     * @param \App\Entity\AccessLog $log
     * @return CargaMime
     */
    public function setLog(AccessLog $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return \App\Entity\AccessLog
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set mime
     *
     * @param \App\Entity\Contenidos $mime
     * @return CargaMime
     */
    public function setMime(Contenidos $mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return \App\Entity\Contenidos
     */
    public function getMime()
    {
        return $this->mime;
    }
}