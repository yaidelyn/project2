<?php

namespace Tex\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ciudades
 *
 * @ORM\Table(name="Ciudades", indexes={@ORM\Index(name="Paises_Codigo", columns={"Paises_Codigo"}), @ORM\Index(name="Ciudad", columns={"Ciudad"})})
 * @ORM\Entity
 */
class Ciudades
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idCiudades", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idciudades;

    /**
     * @var string
     *
     * @ORM\Column(name="Paises_Codigo", type="string", length=2, nullable=false)
     */
    private $paisesCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="Ciudad", type="string", length=100, nullable=false)
     */
    private $ciudad;



    /**
     * Get idciudades
     *
     * @return integer 
     */
    public function getIdciudades()
    {
        return $this->idciudades;
    }

    /**
     * Set paisesCodigo
     *
     * @param string $paisesCodigo
     * @return Ciudades
     */
    public function setPaisesCodigo($paisesCodigo)
    {
        $this->paisesCodigo = $paisesCodigo;

        return $this;
    }

    /**
     * Get paisesCodigo
     *
     * @return string 
     */
    public function getPaisesCodigo()
    {
        return $this->paisesCodigo;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return Ciudades
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
}
