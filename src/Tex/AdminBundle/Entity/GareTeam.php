<?php

namespace Tex\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GareTeam
 *
 * @ORM\Table(name="gare_team")
 * @ORM\Entity
 */
class GareTeam
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
     * @var string
     *
     * @ORM\Column(name="name_rol", type="string", length=255)
     */
    private $name_rol;


    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

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
     * Set name_rol
     *
     * @param string $nameRol
     * @return GareTeam
     */
    public function setNameRol($nameRol)
    {
        $this->name_rol = $nameRol;

        return $this;
    }

    /**
     * Get name_rol
     *
     * @return string 
     */
    public function getNameRol()
    {
        return $this->name_rol;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return GareTeam
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }
}
