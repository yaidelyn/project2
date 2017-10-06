<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Skilloffer
 *
 * @ORM\Table(name="skilloffer", indexes={@ORM\Index(name="IDX_BD2E474353C674EE", columns={"offer_id"})})
 * @ORM\Entity
 */
class Skilloffer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \Offer
     *
     * @ORM\ManyToOne(targetEntity="Offer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="offer_id", referencedColumnName="id")
     * })
     */
    private $offer;


}
