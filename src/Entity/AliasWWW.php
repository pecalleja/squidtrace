<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasWWW
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\AliasWWWRepository")
 */
class AliasWWW
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
     * @ORM\Column(name="alias", type="string", unique=true)
     */
    protected $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string")
     */
    protected $url;

    public function __toString()
    {
        return "<a href=" . $this->getUrl() . ">" . $this->getAlias() . '<\a>';
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
     * Set patron
     *
     * @param string $patron
     * @return AliasWWW
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
     * Set alias
     *
     * @param string $alias
     * @return AliasWWW
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return AliasWWW
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
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
}