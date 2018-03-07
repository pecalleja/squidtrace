<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 * @UniqueEntity("login")
 *  * @UniqueEntity(
 *     fields={"login"},
 *     errorPath="login",
 *     message="Ya existe un usuario registrado con este nombre"
 * )
 */
class Usuario implements UserInterface, \Serializable, EquatableInterface
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
     * @ORM\Column(name="login", type="string", length=25, unique=true)
     */
    protected $login;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="correo", type="string", length=60, nullable=true)
     */
    protected $correo;

    /**
     * @var Grupo
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Grupo", inversedBy="usuarios")
     * @ORM\JoinColumn(name="grupo_id", referencedColumnName="id")
     */
    protected $grupo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AccessLog", mappedBy="usuario")
     */
    protected $access;

    /**
     * @var integer
     *
     * @ORM\Column(name="kuota", type="integer", nullable=true)
     */
    protected $kuota;

    /**
     * @var integer
     *
     * @ORM\Column(name="consumo", type="integer")
     */
    protected $consumo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creado", type="date")
     */
    protected $creado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expira", type="date", nullable=true)
     */
    protected $expira;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="rol", type="string", nullable=true)
     */
    protected $rol;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * Get rol
     *
     * @return string
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set rol
     * @param string $rol
     * @return Usuario
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }


    public function __toString()
    {
        return $this->getNombre();
    }

    public function __construct()
    {
        $this->creado = new \DateTime();
        $this->access = new ArrayCollection();
        $this->rol = "ROLE_USER";
        $this->consumo = 0;
        $this->isActive = true;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Usuario
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            $this->isActive
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
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
     * Set login
     *
     * @param string $login
     * @return Usuario
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Usuario
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set kuota
     *
     * @param integer $kuota
     * @return Usuario
     */
    public function setKuota($kuota)
    {
        $this->kuota = $kuota;

        return $this;
    }

    /**
     * Get kuota
     *
     * @return integer
     */
    public function getKuota()
    {
        return $this->kuota;
    }

    /**
     * Set consumo
     *
     * @param integer $consumo
     * @return Usuario
     */
    public function setConsumo($consumo)
    {
        $this->consumo = $consumo;

        return $this;
    }

    /**
     * Get consumo
     *
     * @return integer
     */
    public function getConsumo()
    {
        return $this->consumo;
    }

    /**
     * Set creado
     *
     * @param \DateTime $creado
     * @return Usuario
     */
    public function setCreado($creado)
    {
        $this->creado = $creado;

        return $this;
    }

    /**
     * Get creado
     *
     * @return \DateTime
     */
    public function getCreado()
    {
        return $this->creado;
    }

    /**
     * Set expira
     *
     * @param \DateTime $expira
     * @return Usuario
     */
    public function setExpira($expira)
    {
        $this->expira = $expira;

        return $this;
    }

    /**
     * Get expira
     *
     * @return \DateTime
     */
    public function getExpira()
    {
        return $this->expira;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set grupo
     *
     * @param Grupo $grupo
     * @return Usuario
     */
    public function setGrupo(Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return Grupo
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Add access
     *
     * @param AccessLog $access
     * @return Usuario
     */
    public function addAcces(AccessLog $access)
    {
        $this->access[] = $access;

        return $this;
    }

    /**
     * Remove access
     *
     * @param AccessLog $access
     */
    public function removeAcces(AccessLog $access)
    {
        $this->access->removeElement($access);
    }

    /**
     * Get access
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccess()
    {
        return $this->access;
    }

    public function isAdmin(){
        if($this->getRol()==='ROLE_ADMIN')
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array($this->getRol());
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->login;
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        /*
        if ($this->salt !== $user->getSalt()) {
            return false;
        }
        */
        if ($this->login !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}