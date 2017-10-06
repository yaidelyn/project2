<?php



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


}
